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
            'reply_content.min' => 'Nội dung phản hồi phải có ít nhất 3 ký tự.',
            'reply_content.max' => 'Nội dung phản hồi không được vượt quá 500 ký tự.',
             ]);

            //  if (Auth::guard('admin')->check()) {
            //     $loggedInAdminId = Auth::guard('admin')->id();

            //     try {
            //     RateReply::create([
            //         'rate_id' => $rate->id,
            //         'admin_id' => $loggedInAdminId,
            //         'reply_content' => $request->input('reply_content'),
            //         ]);
            //         return redirect()->route('rates.show', $rate->id)
            //         ->with('success', 'Phản hồi đã được gửi thành công!');
            //          } catch (\Exception $e) {
            //             return back()->withInput()->with('error', 'Có lỗi xảy ra khi lưu phản hồi. Vui lòng thử lại.');
            //          }
            //          } else {
            //             return redirect()->route('login')
            //             ->with('error', 'Vui lòng đăng nhập với tư cách quản trị viên để phản hồi.');
            //          }
            //         } PHẦN NÀY ĐỂ KHI LÀM ĐĂNG NHẬP XONG.

            $loggedInAdminId = 1; //ID ADMIN ĐỂ TEST
            if (!$loggedInAdminId) {
                return back()->withInput()->with('error', 'Không thể xác định admin. Vui lòng thử lại.');
            }
            try {
                RateReply::create([
                    'rate_id' => $rate->id,
                    'admin_id' => $loggedInAdminId,
                    'reply_content' => $request->input('reply_content'),

            ]);
            return redirect()->route('rates.show', $rate->id)
            ->with('success', 'Phản hồi đã được gửi thành công!');
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Có lỗi xảy ra khi lưu phản hồi. Vui lòng thử lại.');
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RateReply $rateReply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RateReply $rateReply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RateReply $rateReply)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RateReply $rateReply)
    {
        //
    }
}
