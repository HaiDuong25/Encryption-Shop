<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    private $apiBaseUrl = 'https://provinces.open-api.vn/api';

    /**
     * Get districts by province name
     */
    public function getDistricts(Request $request)
    {
        $provinceName = $request->query('province');
        
        if (!$provinceName) {
            return response()->json([]);
        }

        try {
            // Cache key để tránh gọi API quá nhiều
            $cacheKey = 'districts_' . md5($provinceName);
            
            $districts = Cache::remember($cacheKey, 3600, function () use ($provinceName) {
                // Lấy tất cả provinces với districts
                $response = Http::timeout(10)->get($this->apiBaseUrl, ['depth' => 2]);
                
                if (!$response->successful()) {
                    return [];
                }

                $provinces = $response->json();
                
                // Tìm province theo tên
                $targetProvince = collect($provinces)->first(function ($province) use ($provinceName) {
                    return $province['name'] === $provinceName || 
                           str_contains($province['name'], $provinceName) ||
                           str_contains($provinceName, $province['name']);
                });

                if (!$targetProvince || !isset($targetProvince['districts'])) {
                    return [];
                }

                // Trả về danh sách tên districts
                return collect($targetProvince['districts'])->pluck('name')->toArray();
            });

            return response()->json($districts);

        } catch (\Exception $e) {
            Log::error('Error fetching districts: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * Get wards by district name
     */
    public function getWards(Request $request)
    {
        $districtName = $request->query('district');
        
        if (!$districtName) {
            return response()->json([]);
        }

        try {
            // Cache key để tránh gọi API quá nhiều
            $cacheKey = 'wards_' . md5($districtName);
            
            $wards = Cache::remember($cacheKey, 3600, function () use ($districtName) {
                // Lấy tất cả provinces với districts và wards
                $response = Http::timeout(10)->get($this->apiBaseUrl, ['depth' => 3]);
                
                if (!$response->successful()) {
                    return [];
                }

                $provinces = $response->json();
                
                // Tìm district trong tất cả provinces
                foreach ($provinces as $province) {
                    if (!isset($province['districts'])) continue;
                    
                    $targetDistrict = collect($province['districts'])->first(function ($district) use ($districtName) {
                        return $district['name'] === $districtName ||
                               str_contains($district['name'], $districtName) ||
                               str_contains($districtName, $district['name']);
                    });

                    if ($targetDistrict && isset($targetDistrict['wards'])) {
                        return collect($targetDistrict['wards'])->pluck('name')->toArray();
                    }
                }

                return [];
            });

            return response()->json($wards);

        } catch (\Exception $e) {
            Log::error('Error fetching wards: ' . $e->getMessage());
            return response()->json([]);
        }
    }
}
