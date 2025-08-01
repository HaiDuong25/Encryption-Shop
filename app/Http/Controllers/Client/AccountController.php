<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;

class AccountController extends Controller
{
    // Hiển thị trang tài khoản người dùng
    public function index()
    {
        return view('client.account.index');
    }

    // Hiển thị form chỉnh sửa thông tin hồ sơ
    public function editProfile()
    {
        return view('client.account.edit');
    }

    // Cập nhật thông tin hồ sơ
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20|regex:/^([0-9\s\-\+\(\)]*)$/',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            'name.required' => 'Họ tên là bắt buộc.',
            'name.max' => 'Họ tên không được vượt quá 255 ký tự.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được sử dụng.',
            'phone.regex' => 'Số điện thoại không đúng định dạng.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự.',
            'bio.max' => 'Giới thiệu không được vượt quá 1000 ký tự.',
            'date_of_birth.date' => 'Ngày sinh không đúng định dạng.',
            'date_of_birth.before' => 'Ngày sinh phải trước hôm nay.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'avatar.image' => 'Ảnh đại diện phải là file hình ảnh.',
            'avatar.mimes' => 'Ảnh đại diện phải có định dạng: jpeg, png, jpg, gif.',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 2MB.',
            'cover_image.image' => 'Ảnh bìa phải là file hình ảnh.',
            'cover_image.mimes' => 'Ảnh bìa phải có định dạng: jpeg, png, jpg, gif.',
            'cover_image.max' => 'Ảnh bìa không được vượt quá 5MB.',
        ]);

        // Cập nhật thông tin cơ bản
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->bio = $request->bio;
        $user->date_of_birth = $request->date_of_birth;
        $user->gender = $request->gender;

        // Xử lý upload avatar
        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ nếu có
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        // Xử lý upload cover image
        if ($request->hasFile('cover_image')) {
            // Xóa cover image cũ nếu có
            if ($user->cover_image && Storage::disk('public')->exists($user->cover_image)) {
                Storage::disk('public')->delete($user->cover_image);
            }
            
            $coverImagePath = $request->file('cover_image')->store('covers', 'public');
            $user->cover_image = $coverImagePath;
        }

        $user->save();

        return redirect()->route('account.index')->with('success', 'Cập nhật thông tin thành công!');
    }

    // Hiển thị form thay đổi mật khẩu
    public function changePassword()
    {
        return view('client.account.changePassword');
    }

    // Cập nhật mật khẩu người dùng
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ], [
            'current_password.required' => 'Mật khẩu hiện tại là bắt buộc.',
            'new_password.required' => 'Mật khẩu mới là bắt buộc.',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'new_password.regex' => 'Mật khẩu mới phải chứa ít nhất 1 chữ hoa, 1 chữ thường và 1 số.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('account.index')->with('success', 'Đổi mật khẩu thành công!');
    }

    // API để upload cover image qua AJAX
    public function uploadCoverImage(Request $request)
    {
        $request->validate([
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        try {
            $user = Auth::user();
            
            // Xóa cover image cũ nếu có
            if ($user->cover_image && Storage::disk('public')->exists($user->cover_image)) {
                Storage::disk('public')->delete($user->cover_image);
            }
            
            $coverImagePath = $request->file('cover_image')->store('covers', 'public');
            $user->cover_image = $coverImagePath;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật ảnh bìa thành công!',
                'cover_image_url' => asset('storage/' . $coverImagePath)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải ảnh bìa lên.'
            ], 500);
        }
    }

    // Upload avatar qua AJAX
    public function uploadAvatar(Request $request)
    {
        try {
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ], [
                'avatar.required' => 'Vui lòng chọn ảnh đại diện.',
                'avatar.image' => 'File phải là ảnh.',
                'avatar.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg hoặc gif.',
                'avatar.max' => 'Ảnh không được vượt quá 2MB.'
            ]);

            $user = Auth::user();

            // Xóa avatar cũ nếu có
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật ảnh đại diện thành công!',
                'avatar_url' => asset('storage/' . $avatarPath)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải ảnh đại diện lên.'
            ], 500);
        }
    }
}
