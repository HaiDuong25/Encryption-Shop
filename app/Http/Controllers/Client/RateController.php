<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Rate;
use Illuminate\Http\Request;

class RateController extends Controller
{
public function store(Request $request, $productId)
{
    $request->validate([
        'score' => 'required|integer|min:1|max:5',
        'content' => 'nullable|string|max:1000',
    ]);

    $product = Product::findOrFail($productId);

    // Kiểm tra đã đánh giá chưa
    $exists = $product->rates()->where('user_id', auth()->id())->exists();
    if ($exists) {
        return redirect()->route('client.products.show', $product->id)
            ->with('error', 'Bạn đã đánh giá sản phẩm này.');
    }

    $product->rates()->create([
        'user_id' => auth()->id(),
        'score' => $request->score,
        'content' => $request->content,
    ]);

    return redirect()->route('client.products.show', $product->id)
        ->with('success', 'Đánh giá của bạn đã được gửi thành công.');
}



    public function show($id)
{
    $product = Product::with([
        'rates.user',
        'variants.attributeValues.attribute',
        'category',
        'brand'
    ])->findOrFail($id);

    // ... các xử lý khác (ví dụ related products)

    return view('client.products.show', compact('product'));
}

}
