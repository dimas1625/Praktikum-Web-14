<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        try {
            // Grabs token from the Authorization: Bearer <token> header
            $token = JWTAuth::getToken();
            if (! $token) {
                return response()->json([
                    'success' => false,
                    'message' => 'No token provided'
                ], 400);
            }

            // Invalidate it (revoke in blacklist)
            JWTAuth::invalidate($token);

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ]);

        } catch (TokenExpiredException $e) {
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil',
            ], 200);
            
        } catch (TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token is invalid'
            ], 401);

        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not invalidate token'
            ], 500);
        }
    }
}
