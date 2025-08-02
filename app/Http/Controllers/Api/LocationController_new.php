<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    /**
     * Get all provinces from local JSON file
     */
    public function getProvinces()
    {
        try {
            $cacheKey = 'local_provinces_list';
            
            $provinces = Cache::remember($cacheKey, 86400, function () { // Cache 24h
                Log::info('🇻🇳 Loading provinces from local JSON file...');
                
                $filePath = resource_path('data/api/province.json');
                
                if (!file_exists($filePath)) {
                    Log::error('Province JSON file not found: ' . $filePath);
                    return [];
                }
                
                $data = json_decode(file_get_contents($filePath), true);
                
                if (!$data) {
                    Log::error('Invalid province JSON format');
                    return [];
                }

                // Extract province names with type
                $provinceNames = collect($data)->pluck('name_with_type')->values()->toArray();
                Log::info('✅ Local provinces loaded: ' . count($provinceNames));
                
                return $provinceNames;
            });

            return response()->json($provinces);

        } catch (\Exception $e) {
            Log::error('Error loading local provinces: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * Get wards by province name directly from ward.json (skip districts)
     */
    public function getWards(Request $request)
    {
        $provinceName = $request->query('province');
        
        if (!$provinceName) {
            return response()->json([]);
        }

        try {
            $cacheKey = 'local_wards_' . md5($provinceName);
            
            $wards = Cache::remember($cacheKey, 86400, function () use ($provinceName) {
                Log::info("🏘️ Loading wards for province: {$provinceName} from local JSON");
                
                // Load province data to get province code
                $provinceFile = resource_path('data/api/province.json');
                if (!file_exists($provinceFile)) {
                    Log::error('Province JSON file not found');
                    return [];
                }
                
                $provinces = json_decode(file_get_contents($provinceFile), true);
                $targetProvince = collect($provinces)->first(function ($province) use ($provinceName) {
                    return $province['name_with_type'] === $provinceName || 
                           $province['name'] === $provinceName;
                });
                
                if (!$targetProvince) {
                    Log::warning("Province not found: {$provinceName}");
                    return [];
                }
                
                // Load ward data
                $wardFile = resource_path('data/api/ward.json');
                if (!file_exists($wardFile)) {
                    Log::error('Ward JSON file not found');
                    return [];
                }
                
                $wards = json_decode(file_get_contents($wardFile), true);
                
                // Filter wards by province only (skip district level)
                $provinceWards = collect($wards)
                    ->filter(function ($ward) use ($targetProvince) {
                        return isset($ward['parent_code']) && $ward['parent_code'] == $targetProvince['code'];
                    })
                    ->pluck('name_with_type')
                    ->unique()
                    ->values()
                    ->toArray();

                Log::info("✅ Local wards loaded: " . count($provinceWards) . " for {$provinceName}");
                return $provinceWards;
            });

            return response()->json($wards);

        } catch (\Exception $e) {
            Log::error('Error loading local wards: ' . $e->getMessage());
            return response()->json([]);
        }
    }
}
