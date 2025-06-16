<?php

namespace App\Http\Controllers\Admin;

use App\Models\RateReply;
use App\Models\Rate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RateReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function store(Request $request, Rate $rate)
{
    $request->validate([
        'reply_content' => 'required|string|min:3|max:500',
    ], [
        'reply_content.required' => 'Nội dung phản hồi không được để trống.',
        'reply_content.min' => 'Phản hồi phải có ít nhất 3 ký tự.',
        'reply_content.max' => 'Phản hồi không được quá 500 ký tự.',
    ]);

    /** @var \App\Models\User|null $user */
    $user = Auth::user();

    // Nếu chưa đăng nhập hoặc không phải admin thì chặn lại
    if (!$user || !$user->isAdmin()) {
        return redirect()->route('login')->with('error', 'Bạn không có quyền thực hiện hành động này.');
    }

    try {
        RateReply::create([
            'rate_id' => $rate->id,
            'user_id' => $user->id, // gán ID admin
            'reply_content' => $request->input('reply_content'),
        ]);

        return redirect()->route('admin.rates.show', $rate->id)
                         ->with('success', 'Phản hồi đã được gửi thành công!');
    } catch (\Exception $e) {
        return back()->withInput()->with('error', 'Đã xảy ra lỗi khi lưu phản hồi.');
    }
}

}
