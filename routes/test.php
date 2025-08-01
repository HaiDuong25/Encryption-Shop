<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Test route để debug coupon creation
Route::post('/admin/test-coupon', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'Test route works!',
        'request_data' => $request->all(),
        'is_ajax' => $request->ajax(),
        'headers' => [
            'accept' => $request->header('Accept'),
            'content-type' => $request->header('Content-Type'),
            'x-requested-with' => $request->header('X-Requested-With')
        ]
    ]);
})->middleware(['web']);
