<?php

namespace DeveloperUnijaya\RmsSpid\Controllers;

use DeveloperUnijaya\RmsSpid\Models\SpidResponse;
use DeveloperUnijaya\RmsSpid\Models\UserSpid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RmsSpidUserController
{
    public function register(Request $request)
    {
        $response = new SpidResponse;

        $validateData = Validator::make($request->all(), [
            'user_spid_id' => ['required'],
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if ($validateData->fails()) {

            $response->status = 401;
            $response->msg[] = "VALIDATION_ERROR";
            $response->data = $validateData->errors();

        } else {

            $response->msg[] = "VALIDATION_OK";

            try {

                $UserModel = config('auth.providers.users.model');
                $UserModel = new $UserModel;

                $user = $UserModel::firstOrNew(['email' => $request->email]);

                if ($user->exists) {

                    $response->status = 401;
                    $response->msg[] = "USER_ALREADY_EXIST";

                } else {

                    $response->msg[] = "USER_NEW";

                    $user->name = $request->name;
                    $user->password = Hash::make($request->password);

                    if ($user->save()) {

                        $response->msg[] = "USER_SAVE_SUCCESS";

                        $userSpid = UserSpid::firstOrNew(['user_id' => $user->id]);
                        $userSpid->user_spid_id = $request->user_spid_id;

                        if ($userSpid->save()) {

                            $response->msg[] = "USERSPID_SAVE_SUCCESS";
                            $response->status = 200;

                            $response->msg[] = "SUCCESS";
                            $response->data = ['user' => $user, 'userSpid' => $userSpid];
                        } else {

                            $response->status = 401;
                            $response->msg[] = "USERSPID_SAVE_FAILED";
                        }

                    } else {

                        $response->status = 401;
                        $response->msg[] = "USER_SAVE_FAILED";

                    }

                }

            } catch (\Throwable$th) {

                $response->msg[] = "ERROR";
                $response->status = 500;
                $response->msg[] = $th->getMessage();
            }

        }

        return response()->json($response);
    }

    public function profile(Request $request)
    {
        $response = new SpidResponse;

        $validateData = Validator::make($request->all(), [
            'user_spid_id' => ['required'],
        ]);

        if ($validateData->fails()) {

            $response->status = 401;
            $response->msg[] = "VALIDATION_ERROR";
            $response->data = $validateData->errors();

        } else {

            $response->msg[] = "VALIDATION_OK";

            $userSpid = UserSpid::where('user_spid_id', $request->user_spid_id)->first();

            if ($userSpid) {

                $response->msg[] = "USERSPID_FOUND";

                $UserModel = config('auth.providers.users.model');
                $UserModel = new $UserModel;

                if (config('rms-spid.user_profile_relationship')) {

                    $response->msg[] = "CONFIG_USER_PROFILE_RELATIONSHIP_EXIST";
                    $user = $UserModel::with(config('rms-spid.user_profile_relationship'))->where('id', $userSpid->user_id)->first();
                } else {

                    $response->msg[] = "CONFIG_USER_PROFILE_RELATIONSHIP_DOES_NOT_EXIST";
                    $user = $UserModel::where('id', $userSpid->user_id)->first();
                }

                if ($user) {

                    $response->msg[] = "USER_FOUND";
                    $response->status = 200;
                    $response->msg[] = "SUCCESS";
                    $response->data = $user;

                } else {

                    $response->status = 404;
                    $response->msg[] = "USER_NOT_FOUND";

                }
            } else {

                $response->status = 404;
                $response->msg[] = "USERSPID_NOT_FOUND";
            }

        }

        return response()->json($response);
    }

    public function updateSpidId(Request $request)
    {
        $response = new SpidResponse;

        $validateData = Validator::make($request->all(), [
            'user_id' => ['required'],
            'new_user_spid_id' => ['required'],
        ]);

        if ($validateData->fails()) {

            $response->status = 401;
            $response->msg[] = "VALIDATION_ERROR";
            $response->data = $validateData->errors();

        } else {

            $response->msg[] = "VALIDATION_OK";

            try {

                $UserModel = config('auth.providers.users.model');
                $UserModel = new $UserModel;

                $user = $UserModel::where('id', $request->user_id)->first();

                if ($user) {

                    $response->msg[] = "USER_FOUND";

                    $userSpid = UserSpid::firstOrNew(['user_id' => $user->id]);
                    $userSpid->user_spid_id = $request->new_user_spid_id;

                    if ($userSpid->save()) {

                        $response->msg[] = "USERSPID_SAVE_SUCCESS";

                        $response->status = 200;
                        $response->msg[] = "SUCCESS";
                        $response->data = ['user' => $user, 'userSpid' => $userSpid];

                    } else {

                        $response->msg[] = "USERSPID_SAVE_FAILED";
                    }

                } else {

                    $response->status = 401;
                    $response->msg[] = "USER_NOT_FOUND";

                }

            } catch (\Throwable$th) {

                $response->msg[] = "ERROR";
                $response->status = 500;
                $response->msg[] = $th->getMessage();

            }

        }

        return response()->json($response);
    }

    public function check(Request $request)
    {
        $response = new SpidResponse;

        $validateData = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'user_spid_id' => 'required',
        ]);

        if ($validateData->fails()) {

            $response->status = 401;
            $response->msg[] = "VALIDATION_ERROR";
            $response->data = $validateData->errors();

        } else {

            $response->msg[] = "VALIDATION_OK";

            try {

                $UserModel = config('auth.providers.users.model');
                $UserModel = new $UserModel;

                $credentials = ['email' => $request->username, 'password' => $request->password];

                if (Auth::guard('web')->attempt($credentials)) {

                    $response->msg[] = "ATTEMPT_CRED_SUCCESS";

                    $user = $UserModel::where('email', $request->username)->first();

                    $userSpid = UserSpid::firstOrNew(['user_id' => $user->id]);

                    if ($userSpid->exists) {

                        $response->status = 409;
                        $response->msg[] = "ABORT_ALREADY_BIND";
                        $response->data = ['user' => $user];

                    } else {

                        $userSpid->user_spid_id = $request->user_spid_id;

                        if ($userSpid->save()) {

                            $response->status = 200;
                            $response->msg[] = "SUCCESS_BIND";
                            $response->data = ['user' => $user, 'userSpid' => $userSpid];

                        } else {

                            $response->msg[] = "USERSPID_SAVE_FAILED";
                        }

                    }

                } else {

                    $response->status = 409;
                    $response->msg[] = "ATTEMPT_CRED_FAILED";

                }

            } catch (\Throwable$th) {

                $response->msg[] = "ERROR";
                $response->status = 500;
                $response->msg[] = $th->getMessage();
            }
        }

        return response()->json($response);
    }

    public function redirect(Request $request)
    {
        $response = new SpidResponse;

        $validateData = Validator::make($request->all(), [
            'user_id' => ['required'],
            'user_spid_id' => ['required'],
        ]);

        if ($validateData->fails()) {

            $response->status = 401;
            $response->msg[] = "VALIDATION_ERROR";
            $response->data = $validateData->errors();

        } else {

            $response->msg[] = "VALIDATION_OK";

            $userSpid = UserSpid::where('user_id', $request->user_id)->where('user_spid_id', $request->user_spid_id)->first();

            if ($userSpid) {

                $response->msg[] = "USERSPID_FOUND";

                $tokenSuccess = $userSpid->generateRedirectToken();

                if ($tokenSuccess) {

                    $response->msg[] = "REDIRECT_TOKEN_GENERATE_SUCCESS";
                } else {

                    $response->msg[] = "REDIRECT_TOKEN_GENERATE_FAILED";
                }

                $response->status = 200;
                $response->msg[] = "SUCCESS";
                $response->data = [
                    'redirect_token' => $userSpid->redirect_token,
                    'redirect_token_expired_at' => $userSpid->redirect_token_expired_at ? $userSpid->redirect_token_expired_at->format('Y-m-d H:i:s') : null,
                    'redirect_url' => route(config('rms-spid.redirect_sso_success')),
                ];

            } else {

                $response->status = 404;
                $response->msg[] = "USERSPID_NOT_FOUND";

            }
        }

        return response()->json($response);
    }
}
