<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FundRequestController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }

    public function index()
    {
        $userId = session('user_id');
        $userName = session('user_name');
        
        return view('pages.user.fund-request', compact('userId', 'userName'));
    }

    /**
     * ✅ FIXED: Get bank details and return as JSON
     */
   public function getBankDetails()
{
    try {
        $url = "{$this->apiBaseUrl}/admin-bank-details";
        
        $response = Http::timeout(10)->get($url);
        
        if ($response->successful()) {
            $data = $response->json();
            return response()->json([
                'success' => true,
                'data' => $data['data'] ?? []
            ]);
        }
        
        // ✅ Agar API fail hoti hai, toh exact status aur error return karein
        return response()->json([
            'success' => false,
            'message' => 'Admin API Error: Status ' . $response->status(),
            'details' => $response->body() // Yeh asli error dikhayega
        ], 500);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Connection Error: ' . $e->getMessage()
        ], 500);
    }
}

    public function submit(Request $request)
    {
        $userId = session('user_id');
        $userName = session('user_name');

        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Please login first'], 401);
        }

        // try {
            // Prepare data for API
            $data = [
                'user_id' => $userId,
                'username' => $userName,
                'bank_detail_id' => $request->bank_detail_id,
                'payment_mode' => $request->payment_mode,
                'amount' => $request->amount,
                'remark' => $request->remark,
                'mode_of_payment' => $request->mode_of_payment,
                'deposit_bank' => $request->deposit_bank,
                'transaction_no' => $request->transaction_no,
                'deposit_date' => $request->deposit_date,
            ];

            // Handle file upload if present
            if ($request->hasFile('hash_code')) {
                $data['hash_code'] = $request->file('hash_code');
            }

            // Send to admin API
            $response = Http::timeout(30)
                ->attach('hash_code', $data['hash_code'] ?? null)
                ->post("{$this->apiBaseUrl}/fund-request/submit", $data);
                 

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Fund request submitted successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response->json('message', 'Failed to submit request')
            ], 500);
            
        // } catch (\Exception $e) {
        //     Log::error('Fund Request Submit Error: ' . $e->getMessage());
            
        //     return response()->json([
        //         'success' => false,
        //         'message' => $e->getMessage()
        //     ], 500);
        // }
    }
}