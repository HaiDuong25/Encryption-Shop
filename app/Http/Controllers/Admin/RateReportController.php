<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RateReport;
use Illuminate\Support\Facades\DB;

class RateReportController extends Controller
{
    public function manage(Request $request)
    {
        $query = RateReport::with(['user','rate' => function($q){ $q->with('user'); }])->orderBy('created_at','desc');

        if($request->filled('status')){
            $query->where('status',$request->status);
        }
        if($request->filled('reason')){
            $query->where('reason',$request->reason);
        }
        if($request->filled('rate_id')){
            $query->where('rate_id',$request->rate_id);
        }
        if($request->filled('search')){
            $s = $request->search;
            $query->where(function($q) use ($s){
                $q->where('note','like',"%$s%");
                $q->orWhereHas('user', fn($u)=>$u->where('name','like',"%$s%")->orWhere('email','like',"%$s%"));
                $q->orWhereHas('rate', fn($r)=>$r->where('content','like',"%$s%"));
            });
        }

        $reports = $query->paginate(20)->appends($request->query());
        $pendingCount = RateReport::where('status','pending')->count();
        $reasons = ['inappropriate','adult','false_info','spam','abuse','other'];
        return view('admin.rate_reports.index', compact('reports','pendingCount','reasons'));
    }
    public function index($rateId)
    {
    $reports = RateReport::with('user')
            ->where('rate_id', $rateId)
            ->orderBy('created_at','desc')
            ->get()
            ->map(fn($r)=>[
                'id'=>$r->id,
                'user_id'=>$r->user_id,
                'user_name'=>$r->user?->name ?? 'N/A',
        'reason'=>$r->reason_text,
                'note'=>$r->note,
                'status'=>$r->status,
                'created_at'=>$r->created_at->format('d/m/Y H:i')
            ]);
        return response()->json(['success'=>true,'reports'=>$reports]);
    }

    public function update(Request $request, RateReport $rateReport)
    {
        $data = $request->validate([
            'status'=>'required|in:pending,reviewed,dismissed'
        ]);
        $rateReport->status = $data['status'];
        $rateReport->save();

        $rateHidden = false;
        // Nếu báo cáo đã được xử lý (reviewed) thì ẩn luôn đánh giá
        if($data['status'] === 'reviewed' && $rateReport->rate && $rateReport->rate->status != 2){
            $rate = $rateReport->rate;
            $rate->status = 2; // 2 = Ẩn
            $rate->save();
            $rateHidden = true;
        }

        return response()->json([
            'success'=>true,
            'report'=>[
                'id'=>$rateReport->id,
                'status'=>$rateReport->status
            ],
            'rate_hidden'=>$rateHidden,
            'rate_id'=>$rateReport->rate_id
        ]);
    }
}
