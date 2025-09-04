<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RateActionController extends Controller
{
    public function act(Request $request, Rate $rate)
    {
        $request->validate([
            'action' => 'required|in:like,dislike,report'
        ]);

        if(!Auth::check()){
            return response()->json(['success'=>false,'message'=>'Bạn cần đăng nhập'], 401);
        }

        $userId = Auth::id();
        $action = $request->action;

        if($action === 'report' && $rate->user_id === $userId){
            return response()->json(['success'=>false,'message'=>'Bạn không thể tự báo cáo đánh giá của mình'], 403);
        }

        return DB::transaction(function() use ($rate,$userId,$action){
            $exists = DB::table('rate_user_actions')
                ->where('rate_id',$rate->id)
                ->where('user_id',$userId)
                ->where('action',$action)
                ->exists();
            if($exists){
                return response()->json(['success'=>false,'message'=>'Bạn đã thực hiện hành động này']);
            }
            DB::table('rate_user_actions')->insert([
                'rate_id'=>$rate->id,
                'user_id'=>$userId,
                'action'=>$action,
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);
            switch($action){
                case 'like': $rate->addLike(); break;
                case 'dislike': $rate->addDislike(); break;
                case 'report': $rate->addReport(); break;
            }
            return response()->json([
                'success'=>true,
                'message'=>'Thành công',
                'counts'=>[
                    'likes'=>$rate->likes_count,
                    'dislikes'=>$rate->dislikes_count,
                    'reports'=>$rate->reports_count,
                ]
            ]);
        });
    }
}