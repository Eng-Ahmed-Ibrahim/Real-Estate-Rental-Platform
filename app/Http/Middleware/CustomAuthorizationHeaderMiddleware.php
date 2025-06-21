<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthorizationHeaderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the Custom-Authorization header
        $token = $request->header('Custom-Authorization');
        
        // If token is missing, return an Unauthorized response
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Ensure the token is in the format "Bearer <token>"
        if (strpos($token, 'Bearer ') !== 0) {
            $token = 'Bearer ' . $token;
        }

        // Set the Authorization header for Sanctum to pick up
        $request->headers->set('Authorization', $token);

        // Find the token in the database
        $accessToken = PersonalAccessToken::findToken(str_replace('Bearer ', '', $token));

        // If token is invalid or not found, return Unauthorized response
        if (!$accessToken) {
            return response()->json(['error' => 'Unauthorized',"accessToken"=>$accessToken], 401);
        }

        // Retrieve the user associated with the token
        $user = $accessToken->tokenable;

        // If the user is invalid or not found, return Unauthorized response
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Log the user in manually (Authenticate)
        Auth::login($user);

        // Continue to the next middleware
        return $next($request);
    }
}
