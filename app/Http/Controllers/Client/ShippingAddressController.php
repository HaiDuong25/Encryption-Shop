<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShippingAddressController extends Controller
{
    /**
     * Display a listing of user's shipping addresses.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $addresses = Auth::user()->shippingAddresses()->latest()->get();
        return view('client.addresses.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new address.
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $provinces = $this->getVietnameseProvinces();
        return view('client.addresses.create', compact('provinces'));
    }

    /**
     * Store a newly created address.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
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

        // Nếu set làm mặc định, bỏ mặc định các địa chỉ khác của user
        if ($validated['is_default'] ?? false) {
            Auth::user()->shippingAddresses()
                        ->where('is_default', true)
                        ->update(['is_default' => false]);
        }

        $validated['user_id'] = Auth::id();
        $validated['is_default'] = $validated['is_default'] ?? false;

        $address = ShippingAddress::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Địa chỉ giao hàng đã được thêm thành công!',
                'address' => $address
            ]);
        }
        return redirect()->route('client.addresses.index')
            ->with('success', 'Địa chỉ giao hàng đã được thêm thành công!');
    }

    /**
     * Display the specified address.
     */
    public function show(ShippingAddress $address)
    {
        // Kiểm tra quyền sở hữu
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        return view('client.addresses.show', compact('address'));
    }

    /**
     * Show the form for editing the specified address.
     */
    public function edit(ShippingAddress $address)
    {
        // Kiểm tra quyền sở hữu
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $provinces = $this->getVietnameseProvinces();
        return view('client.addresses.edit', compact('address', 'provinces'));
    }

    /**
     * Update the specified address.
     */
    public function update(Request $request, ShippingAddress $address)
    {
        // Kiểm tra quyền sở hữu
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

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

        // Nếu set làm mặc định, bỏ mặc định các địa chỉ khác của user
        if ($validated['is_default'] ?? false) {
            Auth::user()->shippingAddresses()
                        ->where('is_default', true)
                        ->where('id', '!=', $address->id)
                        ->update(['is_default' => false]);
        }

        $validated['is_default'] = $validated['is_default'] ?? false;
        $address->update($validated);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Địa chỉ giao hàng đã được cập nhật thành công!',
                'address' => $address
            ]);
        }
        return redirect()->route('client.addresses.index')
            ->with('success', 'Địa chỉ giao hàng đã được cập nhật thành công!');
    }

    /**
     * Remove the specified address.
     */
    public function destroy(ShippingAddress $address)
    {
        // Kiểm tra quyền sở hữu
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $address->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Địa chỉ giao hàng đã được xóa thành công!'
            ]);
        }
        return redirect()->route('client.addresses.index')
            ->with('success', 'Địa chỉ giao hàng đã được xóa thành công!');
    }

    /**
     * Set address as default
     */
    public function setDefault(ShippingAddress $address)
    {
        // Kiểm tra quyền sở hữu
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        // Bỏ mặc định tất cả địa chỉ khác của user
        Auth::user()->shippingAddresses()
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
        
        // Set địa chỉ này làm mặc định
        $address->update(['is_default' => true]);
        
        return back()->with('success', 'Địa chỉ đã được đặt làm mặc định!');
    }

    /**
     * Get Vietnamese provinces data
     */
    private function getVietnameseProvinces()
    {
        try {
            // Try to get from API first
            $response = Http::timeout(10)->get('https://provinces.open-api.vn/api/p/');
            
            if ($response->successful()) {
                return collect($response->json())->map(function($province) {
                    // Remove prefix for display
                    return preg_replace('/^(Thành phố|Tỉnh)\s+/', '', $province['name']);
                })->toArray();
            }
        } catch (\Exception $e) {
            Log::warning('API provinces failed, using fallback: ' . $e->getMessage());
        }

        // Fallback to static list if API fails
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
