<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Http\Request;

class ShippingAddressController extends Controller
{
    /**
     * Display a listing of users with shipping addresses.
     * Admin chỉ có quyền xem, không được sửa/xóa để tôn trọng quyền riêng tư người dùng.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $users = User::whereHas('shippingAddresses')
            ->with(['shippingAddresses' => function($query) {
                $query->latest();
            }])
            ->when($search, function($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
            })
            ->withCount('shippingAddresses')
            ->latest()
            ->paginate(15);
            
        return view('admin.shipping_addresses.index', compact('users', 'search'));
    }

    /**
     * Display shipping addresses for a specific user.
     */
    public function userAddresses(User $user)
    {
        $addresses = $user->shippingAddresses()->latest()->get();
        return view('admin.shipping_addresses.user_addresses', compact('user', 'addresses'));
    }

    /**
     * Display the specified shipping address details.
     * Admin chỉ có thể xem chi tiết, không được chỉnh sửa.
     */
    public function show(ShippingAddress $shippingAddress)
    {
        $shippingAddress->load('user');
        return view('admin.shipping_addresses.show', compact('shippingAddress'));
    }
}
