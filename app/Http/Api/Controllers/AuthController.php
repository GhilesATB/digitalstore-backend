<?php

namespace App\Http\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'message' => 'name & Password does not match with our record.',
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken("user")->plainTextToken;
        $cookie = cookie('token', $token, 3600);

        return response()->json(['token' => $token], 200)->withCookie($cookie);
    }

    /*public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create(
            array_merge(
                $request->except('password'), [
                    'password' => Hash::make($request->password)
                ]
            )
        );

        Auth::login($user);
        $token = $user->createToken("user")->plainTextToken;
        $cookie = cookie('token', $token, 3600);

        return response()->json(['token' => $token], Response::HTTP_OK)->withCookie($cookie);
    }*/
}
