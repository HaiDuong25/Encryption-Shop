<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class WalletPinController extends Controller
{
    // Hiển thị form thiết lập PIN (nếu chưa có)
    public function showSetupForm()
    {
        $user = Auth::user();
        if ($user->pin_code_hash) {
            return redirect()->route('wallet.index')->with('warning', 'Bạn đã thiết lập PIN.');
        }
        return view('client.wallet.pin-setup');
    }

    // Lưu PIN mới
    public function store(Request $request)
    {
        $request->validate([
            'pin' => ['required','regex:/^\d{6}$/'],
            'pin_confirmation' => ['required','same:pin']
        ]);
        $user = Auth::user();
        if ($user->pin_code_hash) {
            return redirect()->route('wallet.index')->with('error','PIN đã tồn tại.');
        }
        $user->pin_code_hash = Hash::make($request->pin);
        $user->pin_set_at = now();
        $user->pin_failed_attempts = 0;
        $user->pin_locked_until = null;
        $user->save();
        Session::put('wallet_pin_verified_at', now());
        return redirect()->route('wallet.index')->with('success','Thiết lập PIN thành công.');
    }

    // Xác thực PIN (AJAX)
    public function verify(Request $request)
    {
        $request->validate([
            'pin' => ['required','regex:/^\d{6}$/']
        ]);
        $user = Auth::user();
        if (!$user->pin_code_hash) {
            return response()->json(['success'=>false,'message'=>'Chưa thiết lập PIN'], 400);
        }
        if ($user->pin_locked_until && now()->lessThan($user->pin_locked_until)) {
            $minutes = now()->diffInMinutes($user->pin_locked_until);
            return response()->json(['success'=>false,'message'=>'PIN bị khóa. Thử lại sau '.$minutes.' phút.'],423);
        }
        if (Hash::check($request->pin, $user->pin_code_hash)) {
            $user->pin_failed_attempts = 0;
            $user->pin_locked_until = null;
            $user->save();
            Session::put('wallet_pin_verified_at', now());
            return response()->json(['success'=>true,'message'=>'PIN hợp lệ']);
        }
        $user->pin_failed_attempts = $user->pin_failed_attempts + 1;
        if ($user->pin_failed_attempts >= 5) {
            $user->pin_locked_until = now()->addMinutes(15);
            Log::warning('Wallet PIN locked for user '.$user->id);
        }
        $user->save();
        return response()->json(['success'=>false,'message'=>'PIN không đúng'],401);
    }

    // Đổi PIN
    public function change(Request $request)
    {
        $request->validate([
            'old_pin' => ['required','regex:/^\d{6}$/'],
            'new_pin' => ['required','regex:/^\d{6}$/','different:old_pin'],
            'new_pin_confirmation' => ['required','same:new_pin']
        ]);
        $user = Auth::user();
        if (!$user->pin_code_hash || !Hash::check($request->old_pin, $user->pin_code_hash)) {
            return back()->with('error','PIN cũ không đúng.');
        }
        $user->pin_code_hash = Hash::make($request->new_pin);
        $user->pin_set_at = now();
        $user->pin_failed_attempts = 0;
        $user->pin_locked_until = null;
        $user->save();
        Session::put('wallet_pin_verified_at', now());
        return back()->with('success','Đổi PIN thành công.');
    }

    // Hiển thị form quên PIN (xác thực lại bằng mật khẩu tài khoản)
    public function showForgotForm()
    {
        $user = Auth::user();
        if (!$user->pin_code_hash) {
            return redirect()->route('wallet.pin.setup');
        }
        return view('client.wallet.pin-forgot');
    }

    // Xử lý đặt lại PIN khi quên (yêu cầu mật khẩu tài khoản)
    public function resetForgot(Request $request)
    {
        $request->validate([
            'account_password' => ['required','string'],
            'new_pin' => ['required','regex:/^\d{6}$/'],
            'new_pin_confirmation' => ['required','same:new_pin']
        ]);

        $user = Auth::user();
        if (!Hash::check($request->account_password, $user->password)) {
            return back()->with('error','Mật khẩu tài khoản không đúng.');
        }

        $user->pin_code_hash = Hash::make($request->new_pin);
        $user->pin_set_at = now();
        $user->pin_failed_attempts = 0;
        $user->pin_locked_until = null;
        $user->save();
        Session::put('wallet_pin_verified_at', now());
        return redirect()->route('wallet.index')->with('success','Đặt lại PIN thành công.');
    }
}
