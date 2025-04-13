<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AdBlockTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdBlockController extends Controller
{
    /**
     * Enregistre un événement de détection d'AdBlock
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function trackAdBlockDetected(Request $request)
    {
        $userId = Auth::id();
        $userAgent = $request->header('User-Agent');
        $ipAddress = $request->ip();
        $sessionId = $request->session()->getId();
        
        AdBlockTracking::create([
            'user_id' => $userId,
            'user_agent' => $userAgent,
            'ip_address' => $ipAddress,
            'session_id' => $sessionId,
            'status' => 'detected'
        ]);

        return response()->json(['success' => true]);
    }
    
    /**
     * Enregistre un événement d'utilisation sans AdBlock
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function trackAdBlockNotDetected(Request $request)
    {
        $userId = Auth::id();
        $userAgent = $request->header('User-Agent');
        $ipAddress = $request->ip();
        $sessionId = $request->session()->getId();
        
        AdBlockTracking::create([
            'user_id' => $userId,
            'user_agent' => $userAgent,
            'ip_address' => $ipAddress,
            'session_id' => $sessionId,
            'status' => 'not_detected'
        ]);

        return response()->json(['success' => true]);
    }
}
