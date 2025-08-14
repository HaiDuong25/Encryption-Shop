<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.auth');
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            // Thêm xác nhận password_confirmation
            'password' => ['required', 'confirmed', Password::min(6)],
            'agree_terms' => 'accepted' // Đảm bảo người dùng đã đồng ý điều khoản
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => 'active',
        ]);

        // Luôn trả về JSON cho AJAX, vì frontend sẽ xử lý chuyển hướng
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đăng ký thành công! Bạn sẽ được chuyển đến trang đăng nhập.',
                // Gửi kèm URL đăng nhập để JS có thể chuyển hướng
                'redirect' => route('login.form')
            ]);
        }

        // Đối với trường hợp không dùng JS, chuyển hướng tới trang đăng nhập với thông báo
        return redirect()->route('login.form')->with('success', 'Đăng ký thành công. Hãy đăng nhập!');
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
