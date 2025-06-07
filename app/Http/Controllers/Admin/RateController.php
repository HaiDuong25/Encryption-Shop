<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // Đảm bảo bạn đã use Controller cơ sở
use App\Models\Rate; // Import model Rate
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RateController extends Controller
{

    public function index()
    {
        $rates = Rate::with('user')
        ->orderBy('created_at', 'desc')
        ->paginate(15);
        return view('admin.rates.index', compact('rates'));
    }

    public function show(Rate $rate){
        $rate->load(['user', 'replies.admin']);
        return view('admin.rates.show', compact('rate'));
    }

    public function store(Request $request)
    {
        //
    }


    public function edit(Rate $rate)
    {

        $statuses = [
            0 => 'Pending',   // Chờ duyệt
            1 => 'Approved',  // Đã duyệt
            2 => 'Rejected',  // Bị từ chối
        ];

        return view('admin.rates.edit', compact('rate', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rate $rate)
    {
        $validStatuses = [0, 1, 2]; // pending, approved, rejected
        $validatedData = $request->validate([
            'status' => ['required', Rule::in($validStatuses)],
        ]);
        $rate->status = $validatedData['status'];
        $rate->save();
         return redirect()->route('rates.show', $rate->id)
         ->with('success', 'Trạng thái đánh giá đã được cập nhật thành công!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rate $rate)
    {
        try {
            $rate->delete();
            return redirect()->route('rates.index')
                             ->with('success', 'Đánh giá (ID: ' . $rate->id . ') đã được xóa thành công!');
    }catch (\Exception $e) {
        return redirect()->route('rates.index')
                             ->with('error', 'Có lỗi xảy ra khi xóa đánh giá. Vui lòng thử lại.');
        }
    }
}
