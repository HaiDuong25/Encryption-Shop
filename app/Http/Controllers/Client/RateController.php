<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Rate;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function store(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'score' => 'required|integer|min:1|max:5',
            'content' => 'nullable|string|max:1000',
        ]);

        Rate::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'score' => $validated['score'],
            'content' => $validated['content'],
            'status' => 1, // approved ngay lập tức hoặc đổi thành 0 nếu cần duyệt
        ]);

        return redirect()->route('client.products.show', $product->id)
            ->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
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
