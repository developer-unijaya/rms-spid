<?php

namespace DeveloperUnijaya\RmsSpid\Controllers;

use DeveloperUnijaya\RmsSpid\Models\SpidResponse;
use DeveloperUnijaya\RmsSpid\Models\UserSpid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;

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
            'reg_type' => ['required']
        ]);

        if ($validateData->fails()) {

            $response->status = 401;
            $response->message[] = "VALIDATION_ERROR";
            $response->message[] = json_encode($validateData->errors());
            $response->data = $validateData->errors();

        } else {

            $response->message[] = "VALIDATION_OK";

            try {

                $UserModel = config('auth.providers.users.model');
                $UserModel = new $UserModel;

                $user = $UserModel::firstOrNew(['email' => $request->email]);

                if ($user->exists) {

                    $response->status = 401;
                    $response->message[] = "USER_ALREADY_EXIST";

                } else {

                    $response->message[] = "USER_NEW";

                    $user->name = $request->name;
                    $user->password = Hash::make($request->password);

                    if ($user->save()) {

                        $response->message[] = "USER_SAVE_SUCCESS";

                        $userSpid = UserSpid::firstOrNew(['user_id' => $user->id]);
                        $userSpid->src = 'from_spid_reg';
                        $userSpid->user_spid_id = $request->user_spid_id;
                        $userSpid->reg_type = $request->reg_type;
                        $userSpid->reg_json = json_encode($request->except(['spid_key']));

                        if ($userSpid->save()) {

                            $response->status = 200;
                            $response->message[] = "USERSPID_SAVE_SUCCESS";
                            $response->message[] = "SUCCESS";
                            $response->data = ['user' => $user, 'userSpid' => $userSpid];
                        } else {

                            $response->status = 401;
                            $response->message[] = "USERSPID_SAVE_FAILED";
                        }

                    } else {

                        $response->status = 401;
                        $response->message[] = "USER_SAVE_FAILED";

                    }

                }

            } catch (Throwable $th) {

                $response->status = 500;
                $response->message[] = "ERROR";
                $response->message[] = $th->getMessage();
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
            $response->message[] = "VALIDATION_ERROR";
            $response->message[] = json_encode($validateData->errors());
            $response->data = $validateData->errors();

        } else {

            $response->message[] = "VALIDATION_OK";

            $userSpid = UserSpid::where('user_spid_id', $request->user_spid_id)->first();

            if ($userSpid) {

                $response->message[] = "USERSPID_FOUND";

                $UserModel = config('auth.providers.users.model');
                $UserModel = new $UserModel;

                if (config('rms-spid.user_profile_relationship')) {

                    $response->message[] = "CONFIG_USER_PROFILE_RELATIONSHIP_EXIST";
                    $user = $UserModel::with(config('rms-spid.user_profile_relationship'))->where('id', $userSpid->user_id)->first();
                } else {

                    $response->message[] = "CONFIG_USER_PROFILE_RELATIONSHIP_DOES_NOT_EXIST";
                    $user = $UserModel::where('id', $userSpid->user_id)->first();
                }

                if ($user) {

                    $response->status = 200;
                    $response->message[] = "USER_FOUND";
                    $response->message[] = "SUCCESS";
                    $response->data = ['user' => $user, 'userSpid' => $userSpid];

                } else {

                    $response->status = 404;
                    $response->message[] = "USER_NOT_FOUND";

                }
            } else {

                $response->status = 404;
                $response->message[] = "USERSPID_NOT_FOUND";
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
            $response->message[] = "VALIDATION_ERROR";
            $response->message[] = json_encode($validateData->errors());
            $response->data = $validateData->errors();

        } else {

            $response->message[] = "VALIDATION_OK";

            try {

                $UserModel = config('auth.providers.users.model');
                $UserModel = new $UserModel;

                $user = $UserModel::where('id', $request->user_id)->first();

                if ($user) {

                    $response->message[] = "USER_FOUND";

                    $userSpid = UserSpid::firstOrNew(['user_id' => $user->id]);
                    // $userSpid->src = 'from_spid_reg';
                    $userSpid->user_spid_id = $request->new_user_spid_id;

                    if ($userSpid->save()) {

                        $response->status = 200;
                        $response->message[] = "USERSPID_SAVE_SUCCESS";
                        $response->message[] = "SUCCESS";
                        $response->data = ['user' => $user, 'userSpid' => $userSpid];

                    } else {

                        $response->message[] = "USERSPID_SAVE_FAILED";
                    }

                } else {

                    $response->status = 401;
                    $response->message[] = "USER_NOT_FOUND";

                }

            } catch (Throwable $th) {

                $response->status = 500;
                $response->message[] = "ERROR";
                $response->message[] = $th->getMessage();

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
            $response->message[] = "VALIDATION_ERROR";
            $response->message[] = json_encode($validateData->errors());
            $response->data = $validateData->errors();

        } else {

            $response->message[] = "VALIDATION_OK";

            try {

                $UserModel = config('auth.providers.users.model');
                $UserModel = new $UserModel;

                $credentials = ['email' => $request->username, 'password' => $request->password];

                if (Auth::guard('web')->attempt($credentials)) {

                    $response->message[] = "ATTEMPT_CRED_SUCCESS";

                    $user = $UserModel::where('email', $request->username)->first();

                    $userSpid = UserSpid::firstOrNew(['user_id' => $user->id]);
                    $userSpid->src = 'from_spid_bind';

                    if ($userSpid->exists) {

                        $response->status = 409;
                        $response->message[] = "ABORT_ALREADY_BIND";
                        $response->data = ['user' => $user];

                    } else {

                        $userSpid->user_spid_id = $request->user_spid_id;

                        if ($userSpid->save()) {

                            $response->status = 200;
                            $response->message[] = "SUCCESS_BIND";
                            $response->data = ['user' => $user, 'userSpid' => $userSpid];

                        } else {

                            $response->message[] = "USERSPID_SAVE_FAILED";
                        }

                    }

                } else {

                    $response->status = 409;
                    $response->message[] = "ATTEMPT_CRED_FAILED";

                }

            } catch (Throwable $th) {

                $response->status = 500;
                $response->message[] = "ERROR";
                $response->message[] = $th->getMessage();
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
            $response->message[] = "VALIDATION_ERROR";
            $response->message[] = json_encode($validateData->errors());
            $response->data = $validateData->errors();

        } else {

            $response->message[] = "VALIDATION_OK";

            $userSpid = UserSpid::where('user_id', $request->user_id)->where('user_spid_id', $request->user_spid_id)->first();

            if ($userSpid) {

                $response->message[] = "USERSPID_FOUND";

                $tokenSuccess = $userSpid->generateRedirectToken();

                if ($tokenSuccess) {

                    $response->message[] = "REDIRECT_TOKEN_GENERATE_SUCCESS";
                } else {

                    $response->message[] = "REDIRECT_TOKEN_GENERATE_FAILED";
                }

                $response->status = 200;
                $response->message[] = "SUCCESS";
                $response->data = [
                    'redirect_token' => $userSpid->redirect_token,
                    'redirect_token_expired_at' => $userSpid->redirect_token_expired_at ? $userSpid->redirect_token_expired_at->format('Y-m-d H:i:s') : null,
                    'redirect_url' => route(config('rms-spid.redirect_sso_success')),
                ];

            } else {

                $response->status = 404;
                $response->message[] = "USERSPID_NOT_FOUND";

            }
        }

        return response()->json($response);
    }
}
