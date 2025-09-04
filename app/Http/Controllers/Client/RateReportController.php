<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Rate;
use App\Models\RateReport;

class RateReportController extends Controller
{
    public function store(Request $request, Rate $rate)
    {
        if(!Auth::check()){
            return response()->json(['success'=>false,'message'=>'Bạn cần đăng nhập'],401);
        }
        if($rate->user_id === Auth::id()){
            return response()->json(['success'=>false,'message'=>'Bạn không thể báo cáo đánh giá của chính mình'], 403);
        }
        $data = $request->validate([
            'reason' => 'required|string|max:100',
            'note' => 'nullable|string|max:500'
        ]);

        $userId = Auth::id();
        $existing = RateReport::where('rate_id',$rate->id)->where('user_id',$userId)->first();
        if($existing){
            return response()->json(['success'=>false,'message'=>'Bạn đã báo cáo đánh giá này rồi']);
        }

        DB::transaction(function() use ($rate,$userId,$data){
            RateReport::create([
                'rate_id' => $rate->id,
                'user_id' => $userId,
                'reason' => $data['reason'],
                'note' => $data['note'] ?? null,
                'status' => 'pending'
            ]);
            $rate->increment('reports_count');
        });

        return response()->json(['success'=>true,'message'=>'Đã gửi báo cáo. Cảm ơn bạn!']);
    }
}