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
        $response->msg = "Test From " . env('APP_NAME');

        return response()->json($response);
    }

    public function login(Request $request)
    {
        $response = new SpidResponse;

        try {

            $validateData = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required',
            ]);

            if ($validateData->fails()) {

                $response->status = 401;
                $response->msg = "VALIDATION_ERROR";
                $response->data = $validateData->errors();

            } else {

                $credentials = ['email' => $request->username, 'password' => $request->password];

                if (Auth::attempt($credentials)) {

                    $user = User::where('email', $request->username)->first();

                    $response->status = 200;
                    $response->msg = "AUTHENTICATED";
                    $response->data = ['user' => $user, 'auth_token' => $user->createToken("auth_token")->plainTextToken];

                } else {

                    $response->status = 401;
                    $response->msg = "CREDENTIALS_DOES_NOT_MATCH";

                }
            }

        } catch (\Throwable$th) {

            $response->status = 500;
            $response->msg = $th->getMessage();
        }

        return response()->json($response);
    }

    public function me(Request $request)
    {
        $response = new SpidResponse;
        $response->status = 200;
        $response->data = $request->user();

        return response()->json($response);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        $response = new SpidResponse;
        $response->status = 200;
        $response->msg = "LOGGED_OUT";

        return response()->json($response);
    }
}
