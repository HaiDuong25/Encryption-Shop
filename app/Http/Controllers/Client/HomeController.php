<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Banner;
use App\Models\News;
use App\Models\Coupon;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
    // Unset mã giảm giá nếu có (user rời khỏi giỏ hàng/thanh toán)
    session()->forget(['applied_coupon', 'coupon_discount', 'coupon_info']);

    // Lấy 12 sản phẩm nổi bật sắp xếp theo lượt bán và đánh giá
        $products = Product::with([
            'rates' => function ($query) {
                $query->where('status', 1);
            }
        ])
            ->leftJoin('order_details', 'products.id', '=', 'order_details.product_id')
            ->leftJoin('orders', 'order_details.order_id', '=', 'orders.id')
            ->select('products.*')
            ->selectRaw('COALESCE(SUM(order_details.quantity), 0) as total_sales')
            ->selectRaw('COALESCE(SUM(CASE 
                WHEN orders.created_at >= ? AND orders.created_at <= ? AND orders.status IN ("completed", "delivered") 
                THEN order_details.quantity 
                ELSE 0 
            END), 0) as monthly_sales', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ])
            ->withAvg('rates as avg_rating', 'score')
            ->withCount('rates as ratings_count')
            ->where('products.status', 'active')
            ->groupBy('products.id')
            ->orderByDesc('monthly_sales')
            ->orderByDesc('avg_rating')
            ->orderByDesc('total_sales')
            ->take(12)
            ->get();
        $banners = Banner::where('is_active', true)
            ->orderBy('position', 'asc')
            ->get();
        $news = News::where('is_published', true)
            ->latest()
            ->take(6)
            ->get();

        // Lấy danh mục nổi bật
        $categories = Category::where('status', 1)
            ->whereNull('parent_id') // Chỉ lấy danh mục cha
            ->withCount('products')
            ->latest()
            ->take(6)
            ->get();

        // Lấy coupon dựa trên trạng thái đăng nhập
        if (Auth::check()) {
            // Nếu đã đăng nhập, chỉ hiển thị coupon chưa sử dụng
            $coupons = Coupon::availableForUser(Auth::id())
                ->latest()
                ->take(6)
                ->get();

            // Lấy danh sách mã đã lưu của user
            $userSavedCoupons = Auth::user()->savedCoupons()
                ->with('coupon')
                ->get()
                ->pluck('coupon.code')
                ->toArray();
        } else {
            // Nếu chưa đăng nhập, hiển thị tất cả coupon
            $coupons = Coupon::available()
                ->latest()
                ->take(6)
                ->get();

            $userSavedCoupons = [];
        }

        return view('client.home', compact('products', 'banners', 'news', 'coupons', 'categories', 'userSavedCoupons'));
    }
}