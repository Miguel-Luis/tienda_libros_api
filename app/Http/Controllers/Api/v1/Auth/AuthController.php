<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\AuthRegisterRequest;

class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        $user = User::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Usuario registrado con éxito',
            'data' => $user
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['success' => false, 'message' => 'Credenciales invalidas'], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::select('name', 'email')->where('email', $request->email)->first();

        return response()->json(['success' => true, 'data' => $user, 'token' => $token]);
    }

    public function me()
    {
        return response()->json(['success' => true, 'data' => Auth::user()]);
    }

    public function logout()
    {
        Auth::logout();

        return response()->json(['success' => true, 'message' => 'Sesión cerrada con éxito']);
    }
}
