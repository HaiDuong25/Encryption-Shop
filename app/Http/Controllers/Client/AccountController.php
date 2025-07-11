<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
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
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user(); // hoặc auth()->user()
        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return redirect()->route('account.index')->with('success', 'Cập nhật thành công');
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
            'new_password'     => 'required|confirmed|min:8',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu không đúng.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('account.index')->with('success', 'Đổi mật khẩu thành công!');
    }
}
