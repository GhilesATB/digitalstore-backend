<?php

namespace App\Http\Api\Controllers;

use App\Exceptions\EmailAlreadyExistsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

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
        //$user->{"permissions"} = Permission::all()->pluck('name')->toArray();
        $cookie = cookie('token', $token, 3600);

        return response()->json([
            'token' => $token,
            'user' => $user
        ], 200)->withCookie($cookie);
    }

    public function logout(): JsonResponse
    {
        request()->user()->currentAccessToken()->delete();

        Cookie::forget('token');

        return response()->json('', 200);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        if (User::hasEmail($request->input('email'))->exists()) {
            throw new EmailAlreadyExistsException();
        }
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
    }
}
