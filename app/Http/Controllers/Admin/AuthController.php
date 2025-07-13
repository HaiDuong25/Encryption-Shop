<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.auth');
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => 'active',
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đăng ký thành công. Hãy đăng nhập!',
                'user' => $user
            ]);
        }
        return redirect('/auth')->with('success', 'Đăng ký thành công. Hãy đăng nhập!');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Kiểm tra trạng thái
            if ($user->status !== 'active') {
                Auth::logout();
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ với chúng tôi để được hỗ trợ!'
                    ]);
                }
                return back()->withErrors([
                    'email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ với chúng tôi để được hỗ trợ!',
                ]);
            }

            $request->session()->regenerate();

            // Phân quyền chuyển hướng
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đăng nhập thành công!',
                    'redirect' => $user->role === 'admin' ? '/admin/dashboard' : '/',
                    'user' => $user
                ]);
            }
            
            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } else {
                return redirect()->intended('/'); // client
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không đúng.'
            ]);
        }
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
