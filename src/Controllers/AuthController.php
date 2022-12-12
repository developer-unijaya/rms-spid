<?php

namespace DeveloperUnijaya\RmsSpid\Controllers;

// use DeveloperUnijaya\RmsSpid\Models\User;
// use App\Models\User;
use DeveloperUnijaya\RmsSpid\Models\SpidResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AuthController
{
    public function test(Request $request)
    {
        $response = new SpidResponse;
        $response->message[] = "Test From " . env('APP_NAME');

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
                $response->message[] = "VALIDATION_ERROR";
                $response->message[] = json_encode($validateData->errors());
                $response->data = $validateData->errors();

            } else {

                $response->message[] = "VALIDATION_OK";

                $credentials = ['email' => $request->username, 'password' => $request->password];

                if (Auth::attempt($credentials)) {

                    $response->message[] = "CREDENTIALS_MATCH";

                    $UserModel = config('auth.providers.users.model');
                    $UserModel = new $UserModel;

                    $user = $UserModel::where('email', $request->username)->first();

                    $isAllowed = true;
                    if (config('rms-spid.spid_users_id')) {

                        $response->message[] = "CONFIG_SPID_USERS_ID_EXIST";

                        if (!in_array($user->id, config('rms-spid.spid_users_id'))) {
                            $isAllowed = false;
                        } else {
                            $response->message[] = "CREDENTIALS_ALLOWED";
                        }

                    } else {
                        $response->message[] = "CONFIG_SPID_USERS_ID_DOES_NOT_EXIST";
                    }

                    if ($isAllowed) {

                        $response->status = 200;
                        $response->message[] = "AUTHENTICATED";
                        $response->message[] = 'SUCCESS';
                        $response->data = ['user' => $user, 'auth_token' => $user->createToken("auth_token")->plainTextToken];

                    } else {

                        $response->status = 401;
                        $response->message[] = "CREDENTIALS_NOT_ALLOWED";
                    }

                } else {

                    $response->status = 401;
                    $response->message[] = "CREDENTIALS_DOES_NOT_MATCH";

                }
            }

        } catch (Throwable $th) {

            $response->status = 500;
            $response->message[] = 'ERROR';
            $response->message[] = $th->getMessage();
        }

        return response()->json($response);
    }

    public function me(Request $request)
    {
        $response = new SpidResponse;

        try {

            $response->status = 200;
            $response->data = ['user' => $request->user()];
            $response->message[] = 'SUCCESS';

        } catch (Throwable $th) {

            $response->status = 500;
            $response->message[] = 'ERROR';
            $response->message[] = $th->getMessage();
        }

        return response()->json($response);
    }

    public function logout(Request $request)
    {
        $response = new SpidResponse;

        try {

            $request->user()->tokens()->delete();
            $response->status = 200;
            $response->message[] = "LOGGED_OUT";

        } catch (Throwable $th) {

            $response->status = 500;
            $response->message[] = 'ERROR';
            $response->message[] = $th->getMessage();
        }

        return response()->json($response);
    }
}
