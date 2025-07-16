<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\Recaptcha;
use Illuminate\Support\Facades\Http; 

class VerifyCaptchaMiddleware
{

   public function handle(Request $request, Closure $next)
    {//dd( $request);
        // Retrieve the reCAPTCHA token from the request
        $token = $request->input('recaptcha_token'); // Expect token in a custom header
 dd($token);
        // Validate the token with Google's reCAPTCHA API
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_V3_SECRET_KEY'),
            'response' => $token,
        ]);

        $result = $response->json();
      
        // Check if the reCAPTCHA validation fails
        if (!$result['success'] || ($result['score'] ?? 0) < 0.8) {
            return redirect('/blocked'); // Redirect bots to a "blocked" page
        }

        return $next($request); // Allow valid users to proceed
    }
}
