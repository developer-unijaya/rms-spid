<?php

namespace DeveloperUnijaya\RmsSpid\Controllers;

// use DeveloperUnijaya\RmsSpid\Models\User;
// use App\Models\User;
use DeveloperUnijaya\RmsSpid\Models\SpidResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RmsSpidAuthController
{
    public function test(Request $request)
    {
        $response = new SpidResponse;
        $response->msg[] = "Test From " . env('APP_NAME');

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
                $response->msg[] = "VALIDATION_ERROR";
                $response->msg[] = json_encode($validateData->errors());
                $response->data = $validateData->errors();

            } else {

                $response->msg[] = "VALIDATION_OK";

                $credentials = ['email' => $request->username, 'password' => $request->password];

                if (Auth::attempt($credentials)) {

                    $response->msg[] = "CREDENTIALS_MATCH";

                    $UserModel = config('auth.providers.users.model');
                    $UserModel = new $UserModel;

                    $user = $UserModel::where('email', $request->username)->first();

                    $isAllowed = true;
                    if (config('rms-spid.spid_users_id')) {

                        $response->msg[] = "CONFIG_SPID_USERS_ID_EXIST";

                        if (!in_array($user->id, config('rms-spid.spid_users_id'))) {
                            $isAllowed = false;
                        } else {
                            $response->msg[] = "CREDENTIALS_ALLOWED";
                        }

                    } else {
                        $response->msg[] = "CONFIG_SPID_USERS_ID_DOES_NOT_EXIST";
                    }

                    if ($isAllowed) {

                        $response->status = 200;
                        $response->msg[] = "AUTHENTICATED";
                        $response->msg[] = 'SUCCESS';
                        $response->data = ['user' => $user, 'auth_token' => $user->createToken("auth_token")->plainTextToken];

                    } else {

                        $response->status = 401;
                        $response->msg[] = "CREDENTIALS_NOT_ALLOWED";
                    }

                } else {

                    $response->status = 401;
                    $response->msg[] = "CREDENTIALS_DOES_NOT_MATCH";

                }
            }

        } catch (\Throwable$th) {

            $response->msg[] = 'ERROR';
            $response->status = 500;
            $response->msg[] = $th->getMessage();
        }

        return response()->json($response);
    }

    public function me(Request $request)
    {
        // SpidKey::check($request);

        $response = new SpidResponse;

        try {

            $response->status = 200;
            $response->data = $request->user();
            $response->msg[] = 'SUCCESS';

        } catch (\Throwable$th) {

            $response->msg[] = 'ERROR';
            $response->status = 500;
            $response->msg[] = $th->getMessage();

        }

        return response()->json($response);
    }

    public function logout(Request $request)
    {
        $response = new SpidResponse;

        try {

            $request->user()->tokens()->delete();
            $response->status = 200;
            $response->msg[] = "LOGGED_OUT";

        } catch (\Throwable$th) {

            $response->msg[] = 'ERROR';
            $response->status = 500;
            $response->msg[] = $th->getMessage();

        }

        return response()->json($response);
    }
}
