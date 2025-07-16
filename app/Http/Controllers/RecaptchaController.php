<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RecaptchaController extends Controller
{
    public function validateV3Token(Request $request)
    {//dd($request);
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_V3_SECRET_KEY'),
            'response' => $request->token,
        ]);

        $result = $response->json();

        return response()->json([
            'success' => $result['success'] ?? false,
            'score' => $result['score'] ?? 0,
            'action' => $result['action'] ?? '',
        ]);
    }
    
}
