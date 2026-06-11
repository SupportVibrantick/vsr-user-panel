<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MatchingIncomeController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }

    /**
     * Show Matching Income page
     */
    public function index()
    {
        return view('pages.user.matching-income');
    }

    /**
     * Get Matching Income data via API
     */
    public function getMatchingIncomeData(Request $request)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first'
            ], 401);
        }

        try {
            // Call Admin Panel API
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/matching-income", [
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