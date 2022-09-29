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

            $validateUser = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required',
            ]);

            if ($validateUser->fails()) {

                $response->status = 401;
                $response->msg = "Validation Error";
                $response->data = $validateUser->errors();

            } else {

                $credentials = ['email' => $request->username, 'password' => $request->password];

                if (Auth::attempt($credentials)) {

                    $user = User::where('email', $request->username)->first();

                    $data['user'] = $user;
                    $data['auth_token'] = $user->createToken("auth_token")->plainTextToken;

                    $response->status = 200;
                    $response->msg = "Authenticated";
                    $response->data = $data;

                } else {
                    $response->status = 401;
                    $response->msg = "Credentials does not match with our record";
                }
            }

        } catch (\Throwable $th) {

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
        $response->msg = "Logged out";

        return response()->json($response);
    }
}
