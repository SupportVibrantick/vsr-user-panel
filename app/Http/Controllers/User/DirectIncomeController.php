<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DirectIncomeController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }

    /**
     * Show Direct Income page
     */
    public function index()
    {
        return view('pages.user.direct-income');
    }

    /**
     * Get Direct Income data via API
     */
    public function getDirectIncomeData(Request $request)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first'
            ], 401);
        }

        try {
            // ✅ Call Admin Panel API
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/direct-income", [
                'user_id' => $userId,
                'date_from' => $request->date_from ?? '',
                'date_to' => $request->date_to ?? '',
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data from API'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}