<?php

namespace DeveloperUnijaya\RmsSpid\Controllers;

// use DeveloperUnijaya\RmsSpid\Models\User;
use App\Models\User;
use DeveloperUnijaya\RmsSpid\Models\SpidResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RmsSpidAuthController
{
    public function test(Request $request)
    {
        $response = new SpidResponse;

        return response()->json($response);
    }

    public function login(Request $request)
    {
        try {

            $validateUser = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors(),
                ], 401);
            }

            $credentials = ['email' => $request->username, 'password' => $request->password];

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->username)->first();

            return response()->json([
                'status' => true,
                'message' => 'success',
                'token' => $user->createToken("auth_token")->plainTextToken,
            ], 200);

        } catch (\Throwable$th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function me(Request $request)
    {
        $response = new SpidResponse;
        $response->data = $request->user();

        return response()->json($response);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'logged out',
        ], 200);
    }
}
