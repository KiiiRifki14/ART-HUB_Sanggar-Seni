<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeocodingController extends Controller
{
    /**
     * Search addresses using Nominatim API (OpenStreetMap)
     *
     * Query Parameters:
     * - q: search query (address, city, etc.)
     * - limit: max results (default: 5)
     *
     * Returns: Array of results with address, latitude, longitude
     */
    public function searchAddress(Request $request)
    {
        $query = $request->input('q', '');
        $limit = $request->input('limit', 5);

        if (strlen($query) < 3) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal 3 karakter untuk pencarian',
                'results' => []
            ], 400);
        }

        try {
            // Call Nominatim API (OpenStreetMap)
            // https://nominatim.openstreetmap.org/search
            $response = Http::withHeaders([
                'User-Agent' => 'ART-HUB-Sanggar-Seni (Laravel App)'
            ])->get('https://nominatim.openstreetmap.org/search', [
                'q' => $query,
                'format' => 'json',
                'limit' => $limit,
                'addressdetails' => 1,
                'countrycodes' => 'id' // Prioritize Indonesia
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghubungi server peta',
                    'results' => []
                ], 500);
            }

            $results = $response->json();

            // Format results for easier frontend consumption
            $formatted = array_map(function ($item) {
                return [
                    'id' => $item['osm_id'] ?? null,
                    'address' => $item['address'] ?? $item['display_name'] ?? '',
                    'display_name' => $item['display_name'] ?? '',
                    'latitude' => (float) ($item['lat'] ?? 0),
                    'longitude' => (float) ($item['lon'] ?? 0),
                    'type' => $item['type'] ?? 'location',
                    'bbox' => $item['boundingbox'] ?? null // For map bounds
                ];
            }, $results);

            return response()->json([
                'success' => true,
                'message' => count($formatted) . ' hasil ditemukan',
                'results' => $formatted
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'results' => []
            ], 500);
        }
    }

    /**
     * Reverse Geocoding - Get address from coordinates
     *
     * Query Parameters:
     * - latitude: latitude coordinate
     * - longitude: longitude coordinate
     *
     * Returns: Address information for the coordinates
     */
    public function reverseGeocode(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        // Validate coordinates
        if (!is_numeric($latitude) || !is_numeric($longitude)) {
            return response()->json([
                'success' => false,
                'message' => 'Koordinat tidak valid',
                'result' => null
            ], 400);
        }

        // Validate latitude range (-90 to 90) and longitude range (-180 to 180)
        if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
            return response()->json([
                'success' => false,
                'message' => 'Koordinat di luar jangkauan',
                'result' => null
            ], 400);
        }

        try {
            // Call Nominatim Reverse API
            // https://nominatim.openstreetmap.org/reverse
            $response = Http::withHeaders([
                'User-Agent' => 'ART-HUB-Sanggar-Seni (Laravel App)'
            ])->get('https://nominatim.openstreetmap.org/reverse', [
                'lat' => $latitude,
                'lon' => $longitude,
                'format' => 'json',
                'addressdetails' => 1,
                'zoom' => 18
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mendapatkan alamat',
                    'result' => null
                ], 500);
            }

            $data = $response->json();

            return response()->json([
                'success' => true,
                'message' => 'Alamat ditemukan',
                'result' => [
                    'latitude' => (float) $latitude,
                    'longitude' => (float) $longitude,
                    'address' => $data['address'] ?? $data['display_name'] ?? 'Lokasi tidak dikenal',
                    'display_name' => $data['display_name'] ?? '',
                    'type' => $data['type'] ?? 'location'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'result' => null
            ], 500);
        }
    }

    /**
     * Autocomplete address suggestions (lightweight version of search)
     *
     * Query Parameters:
     * - q: search query
     *
     * Returns: Simple array of addresses
     */
    public function autocomplete(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 3) {
            return response()->json([]);
        }

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'ART-HUB-Sanggar-Seni (Laravel App)'
            ])->get('https://nominatim.openstreetmap.org/search', [
                'q' => $query,
                'format' => 'json',
                'limit' => 8,
                'countrycodes' => 'id'
            ]);

            if (!$response->successful()) {
                return response()->json([]);
            }

            $results = array_map(function ($item) {
                return [
                    'label' => $item['display_name'] ?? '',
                    'value' => [
                        'lat' => (float) ($item['lat'] ?? 0),
                        'lon' => (float) ($item['lon'] ?? 0),
                        'display_name' => $item['display_name'] ?? ''
                    ]
                ];
            }, $response->json());

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }
}
