<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'name'      => ['required', 'string', 'min:5', 'max:150'],
            'email'     => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'nik'       => ['required', 'string', 'max:15', 'regex:/^[0-9]+$/', 'unique:users,nik'],
            'password'  => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'passwordConfirm'  => ['required', 'same:password'],
        ]);

        if($validate->fails()){
            throw new HttpResponseException(response([
                'errors'   => $validate->errors()
            ], 422));
        }

        $request['name'] = strtolower($request['name']);
        User::create($request->all());

        return response()->json([
            'message'   => 'success register',
            'data'      => $request->all()
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'email'    => ['string', Rule::requiredIf(empty($request->nik))], // email harus ada jika nik kosong
            'nik'      => ['string', Rule::requiredIf(empty($request->email))], // nik harus ada jika email kosong
            'password' => ['required', 'string'],
        ]);

        if($validate->fails()){
            throw new HttpResponseException(response([
                'errors'   => $validate->errors()
            ], 422));
        }

        if (!Auth::attempt($request->only('email', 'password', 'nik'))){
            throw new HttpResponseException(response([
                'errors'   => 'user credentials not valid'
            ], 403));
        }

        $user = User::where('email', $request->email)->orWhere('nik', $request->nik)->first();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'   => 'success login',
            'data'      => [
                'user'  => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    public function show(): JsonResponse
    {
        return response()->json([
            'message'   => 'success get user detail',
            'data'      => [
                'user'  => Auth::user(),
            ]
        ]);
    }

    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message'   => 'success logout',
        ]);
    }
}
