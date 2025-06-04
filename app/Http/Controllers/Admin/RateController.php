<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // Đảm bảo bạn đã use Controller cơ sở
use App\Models\Rate; // Import model Rate
use Illuminate\Http\Request;

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
   

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rate $rate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rate $rate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rate $rate)
    {
        //
    }
}
