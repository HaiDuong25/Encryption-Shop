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
     * Show all shipping addresses for a specific user.
     */
    public function userAddresses(User $user)
    {
        $addresses = $user->shippingAddresses()->latest()->get();
        return view('admin.shipping_addresses.user_addresses', compact('user', 'addresses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = $this->getVietnameseProvinces();
        return view('admin.shipping_addresses.create', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^[0-9]{10,11}$/',
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'address_detail' => 'required|string',
            'is_default' => 'nullable|boolean',
            'note' => 'nullable|string',
        ]);

        // Nếu set làm mặc định, bỏ mặc định các địa chỉ khác của user này
        if ($validated['is_default'] ?? false) {
            ShippingAddress::where('user_id', $validated['user_id'])
                          ->where('is_default', true)
                          ->update(['is_default' => false]);
        }

        $validated['is_default'] = $validated['is_default'] ?? false;

        ShippingAddress::create($validated);

        // Redirect về trang địa chỉ của user nếu có user_id, ngược lại về index
        if ($request->input('user_id')) {
            $user = User::find($request->input('user_id'));
            return redirect()->route('shipping-addresses.user-addresses', $user)
                ->with('success', 'Địa chỉ giao hàng đã được tạo thành công!');
        }

        return redirect()->route('shipping-addresses.index')
            ->with('success', 'Địa chỉ giao hàng đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingAddress $shippingAddress)
    {
        return view('admin.shipping_addresses.show', compact('shippingAddress'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShippingAddress $shippingAddress)
    {
        $provinces = $this->getVietnameseProvinces();
        return view('admin.shipping_addresses.edit', compact('shippingAddress', 'provinces'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShippingAddress $shippingAddress)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^[0-9]{10,11}$/',
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'address_detail' => 'required|string',
            'is_default' => 'nullable|boolean',
            'note' => 'nullable|string',
        ]);

        // Nếu set làm mặc định, bỏ mặc định các địa chỉ khác của cùng user
        if ($validated['is_default'] ?? false) {
            ShippingAddress::where('user_id', $shippingAddress->user_id)
                          ->where('is_default', true)
                          ->where('id', '!=', $shippingAddress->id)
                          ->update(['is_default' => false]);
        }

        $validated['is_default'] = $validated['is_default'] ?? false;

        $shippingAddress->update($validated);

        return redirect()->route('shipping-addresses.index')
            ->with('success', 'Địa chỉ giao hàng đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingAddress $shippingAddress)
    {
        $shippingAddress->delete();
        
        return redirect()->route('shipping-addresses.index')
            ->with('success', 'Địa chỉ giao hàng đã được xóa thành công!');
    }

    /**
     * Set address as default
     */
    public function setDefault(ShippingAddress $shippingAddress)
    {
        // Bỏ mặc định tất cả địa chỉ khác của cùng user
        ShippingAddress::where('user_id', $shippingAddress->user_id)
                      ->where('is_default', true)
                      ->update(['is_default' => false]);
        
        // Set địa chỉ này làm mặc định
        $shippingAddress->update(['is_default' => true]);
        
        return back()->with('success', 'Địa chỉ đã được đặt làm mặc định!');
    }

    /**
     * Get Vietnamese provinces data
     */
    private function getVietnameseProvinces()
    {
        return [
            // Thành phố trực thuộc trung ương
            'Hà Nội', 'TP Hồ Chí Minh', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ',
            // Các tỉnh thành khác
            'An Giang', 'Bà Rịa - Vũng Tàu', 'Bắc Giang', 'Bắc Kạn', 'Bạc Liêu',
            'Bắc Ninh', 'Bến Tre', 'Bình Định', 'Bình Dương', 'Bình Phước',
            'Bình Thuận', 'Cà Mau', 'Cao Bằng', 'Đắk Lắk', 'Đắk Nông',
            'Điện Biên', 'Đồng Nai', 'Đồng Tháp', 'Gia Lai', 'Hà Giang',
            'Hà Nam', 'Hà Tĩnh', 'Hải Dương', 'Hậu Giang', 'Hòa Bình',
            'Hưng Yên', 'Khánh Hòa', 'Kiên Giang', 'Kon Tum', 'Lai Châu',
            'Lâm Đồng', 'Lạng Sơn', 'Lào Cai', 'Long An', 'Nam Định',
            'Nghệ An', 'Ninh Bình', 'Ninh Thuận', 'Phú Thọ', 'Phú Yên',
            'Quảng Bình', 'Quảng Nam', 'Quảng Ngãi', 'Quảng Ninh', 'Quảng Trị',
            'Sóc Trăng', 'Sơn La', 'Tây Ninh', 'Thái Bình', 'Thái Nguyên',
            'Thanh Hóa', 'Thừa Thiên Huế', 'Tiền Giang', 'Trà Vinh', 'Tuyên Quang',
            'Vĩnh Long', 'Vĩnh Phúc', 'Yên Bái'
        ];
    }
}
