<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (!$token = auth()->attempt([
            'email'     => $request->email,
            'password'  => $request->password,
        ])) {
            return response()->error('Invalid login credentials.', 401);
        }

        return response()->ok([
            'token'         => $token,
            'token_type'    => 'bearer',
            'expires'       => auth()->factory()->getTTL(),
        ]);
    }

    public function me()
    {
        $user = auth()->user();
        return response()->ok($user);
    }
}
