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
     * Get all provinces
     */
    public function getProvinces()
    {
        try {
            $cacheKey = 'provinces_list';
            
            $provinces = Cache::remember($cacheKey, 3600, function () {
                $response = Http::timeout(15)->get($this->apiBaseUrl . '/p/');
                
                if (!$response->successful()) {
                    return [];
                }

                return collect($response->json())->pluck('name')->toArray();
            });

            return response()->json($provinces);

        } catch (\Exception $e) {
            Log::error('Error fetching provinces: ' . $e->getMessage());
            return response()->json([]);
        }
    }

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
            $cacheKey = 'districts_' . md5($provinceName);
            
            $districts = Cache::remember($cacheKey, 3600, function () use ($provinceName) {
                $response = Http::timeout(15)->get($this->apiBaseUrl . '/?depth=2');
                
                if (!$response->successful()) {
                    Log::error('API call failed with status: ' . $response->status());
                    return [];
                }

                $provinces = $response->json();
                
                if (!is_array($provinces)) {
                    Log::error('Invalid API response format');
                    return [];
                }

                // Find province with flexible matching
                $targetProvince = collect($provinces)->first(function ($province) use ($provinceName) {
                    if (!isset($province['name'])) return false;
                    
                    $apiName = $province['name'];
                    
                    // Exact match
                    if ($apiName === $provinceName) return true;
                    
                    // Remove prefixes for comparison
                    $cleanApiName = preg_replace('/^(Thành phố|Tỉnh)\s+/', '', $apiName);
                    $cleanInputName = preg_replace('/^(Thành phố|Tỉnh)\s+/', '', $provinceName);
                    
                    return $cleanApiName === $cleanInputName;
                });

                if (!$targetProvince || !isset($targetProvince['districts'])) {
                    Log::warning('Province not found or no districts: ' . $provinceName);
                    return [];
                }

                return collect($targetProvince['districts'])
                    ->pluck('name')
                    ->filter()
                    ->values()
                    ->toArray();
            });

            return response()->json($districts);

        } catch (\Exception $e) {
            Log::error('Error fetching districts: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * Get wards by district name and province name
     */
    public function getWards(Request $request)
    {
        $districtName = $request->query('district');
        $provinceName = $request->query('province');
        
        if (!$districtName || !$provinceName) {
            return response()->json([]);
        }

        try {
            $cacheKey = 'wards_' . md5($districtName . '_' . $provinceName);
            
            $wards = Cache::remember($cacheKey, 3600, function () use ($districtName, $provinceName) {
                $response = Http::timeout(20)->get($this->apiBaseUrl . '/?depth=3');
                
                if (!$response->successful()) {
                    Log::error('API call failed with status: ' . $response->status());
                    return [];
                }

                $provinces = $response->json();
                
                if (!is_array($provinces)) {
                    Log::error('Invalid API response format');
                    return [];
                }

                // Find province with flexible matching (same as getDistricts)
                $targetProvince = collect($provinces)->first(function ($province) use ($provinceName) {
                    if (!isset($province['name'])) return false;
                    
                    $apiName = $province['name'];
                    
                    // Exact match
                    if ($apiName === $provinceName) return true;
                    
                    // Remove prefixes for comparison
                    $cleanApiName = preg_replace('/^(Thành phố|Tỉnh)\s+/', '', $apiName);
                    $cleanInputName = preg_replace('/^(Thành phố|Tỉnh)\s+/', '', $provinceName);
                    
                    return $cleanApiName === $cleanInputName;
                });

                if (!$targetProvince || !isset($targetProvince['districts'])) {
                    Log::warning('Province not found: ' . $provinceName);
                    return [];
                }

                $targetDistrict = collect($targetProvince['districts'])->first(function ($district) use ($districtName) {
                    return isset($district['name']) && $district['name'] === $districtName;
                });

                if (!$targetDistrict || !isset($targetDistrict['wards'])) {
                    Log::warning('District not found or no wards: ' . $districtName);
                    return [];
                }

                return collect($targetDistrict['wards'])
                    ->pluck('name')
                    ->filter()
                    ->values()
                    ->toArray();
            });

            return response()->json($wards);

        } catch (\Exception $e) {
            Log::error('Error fetching wards: ' . $e->getMessage());
            return response()->json([]);
        }
    }
}
