<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,staff,user',
            'phone'    => 'nullable|string',
            'address'  => 'nullable|string',
            'status'   => 'required|in:active,inactive,pending',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'

        ]);
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('users', 'public');
        } else {
            $path = null;
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'phone'    => $request->phone,
            'address'  => $request->address,
            'status'   => $request->status,
            'avatar'   => $path
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm người dùng',
                'user' => $user
            ]);
        }
        return redirect()->route('users.index')->with('success', 'Đã thêm người dùng');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'role'     => 'required|in:admin,staff,user',
            'phone'    => 'nullable|string',
            'address'  => 'nullable|string',
            'status'   => 'required|in:active,inactive,pending',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);
        $updateData = [
            'name'    => $request->name,
            'email'   => $request->email,
            'role'    => $request->role,
            'status'  => $request->status,
            'phone'   => $request->phone,
            'address' => $request->address,
        ];
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $updateData['avatar'] = $request->file('avatar')->store('users', 'public');
        }

        $user->update($updateData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật người dùng',
                'user' => $user
            ]);
        }
        return redirect()->route('users.index')->with('success', 'Đã cập nhật người dùng');
    }

    public function toggle(User $user)
    {
        if ($user->role === 'admin') {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể khóa/mở khóa admin!'
                ]);
            }
            return back()->with('error', 'Không thể khóa/mở khóa admin!');
        }
        // Toggle status giữa active và inactive
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $user->status === 'active' ? 'Đã mở khóa!' : 'Đã khóa!',
                'status' => $user->status
            ]);
        }
        return back()->with('success', $user->status === 'active' ? 'Đã mở khóa!' : 'Đã khóa!');
    }


    public function destroy(User $user)
    {
        // Nếu user là admin thì không cho xóa
        if ($user->role === 'admin') {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa tài khoản admin!'
                ]);
            }
            return redirect()->route('users.index')->with('error', 'Không thể xóa tài khoản admin!');
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xoá người dùng'
            ]);
        }
        return redirect()->route('users.index')->with('success', 'Đã xoá người dùng');
    }
}
