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
        $hasExistingAddresses = Auth::user()->shippingAddresses()->count() > 0;
        
        return view('client.addresses.create', compact('provinces', 'hasExistingAddresses'));
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
            'ward' => 'required|string|max:255',
            'address_detail' => 'required|string',
            'is_default' => 'nullable|boolean',
            'note' => 'nullable|string',
        ]);

        // Kiểm tra xem user có địa chỉ nào chưa
        $userAddressCount = Auth::user()->shippingAddresses()->count();
        
        // Nếu đây là địa chỉ đầu tiên, tự động set làm mặc định
        if ($userAddressCount === 0) {
            $validated['is_default'] = true;
        }
        
        // Nếu set làm mặc định, bỏ mặc định các địa chỉ khác của user
        if ($validated['is_default'] ?? false) {
            Auth::user()->shippingAddresses()
                        ->where('is_default', true)
                        ->update(['is_default' => false]);
        }

        $validated['user_id'] = Auth::id();
        $validated['is_default'] = $validated['is_default'] ?? false;
        $validated['district'] = null; // Set district to null for 2-level system

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
        $totalAddresses = Auth::user()->shippingAddresses()->count();
        
        return view('client.addresses.edit', compact('address', 'provinces', 'totalAddresses'));
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
            'ward' => 'required|string|max:255',
            'address_detail' => 'required|string',
            'is_default' => 'nullable|boolean',
            'note' => 'nullable|string',
        ]);

        // Kiểm tra nếu chỉ có 1 địa chỉ và đang cố gắng bỏ mặc định
        $totalAddresses = Auth::user()->shippingAddresses()->count();
        if ($totalAddresses == 1 && $address->is_default && !($validated['is_default'] ?? false)) {
            $validated['is_default'] = true; // Force keep default
        }

        // Nếu set làm mặc định, bỏ mặc định các địa chỉ khác của user
        if ($validated['is_default'] ?? false) {
            Auth::user()->shippingAddresses()
                        ->where('is_default', true)
                        ->where('id', '!=', $address->id)
                        ->update(['is_default' => false]);
        }

        $validated['is_default'] = $validated['is_default'] ?? false;
        // Add explicit district field setting for 2-level system
        $validated['district'] = null;

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
     * Get Vietnamese provinces data from LocationController API directly
     */
    private function getVietnameseProvinces()
    {
        try {
            // Create LocationController instance and call method directly
            $locationController = new \App\Http\Controllers\Api\LocationController();
            $response = $locationController->getProvinces();
            
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getContent(), true);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to load provinces from LocationController: ' . $e->getMessage());
        }

        // Fallback to static list if everything fails
        return [
            'Thành phố Hà Nội', 'Thành phố Hồ Chí Minh', 'Thành phố Đà Nẵng', 
            'Thành phố Hải Phòng', 'Thành phố Cần Thơ',
            'Tỉnh An Giang', 'Tỉnh Bà Rịa - Vũng Tàu', 'Tỉnh Bắc Giang', 'Tỉnh Bắc Kạn', 'Tỉnh Bạc Liêu',
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
