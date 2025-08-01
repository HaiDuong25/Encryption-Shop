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
        $products = Product::where('status', 'active')->latest()->take(12)->get();
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
