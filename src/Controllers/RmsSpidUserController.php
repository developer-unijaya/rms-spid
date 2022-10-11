<?php

namespace DeveloperUnijaya\RmsSpid\Controllers;

use DeveloperUnijaya\RmsSpid\Models\SpidResponse;
use DeveloperUnijaya\RmsSpid\Models\UserSpid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

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
            $response->msg = "VALIDATION_ERROR";
            $response->data = $validateData->errors();

        } else {

            try {

                $UserModel = config('auth.providers.users.model');
                $UserModel = new $UserModel;

                $user = $UserModel::firstOrNew(['email' => $request->email]);

                if ($user->exists) {

                    $response->status = 401;
                    $response->msg = "USER_ALREADY_EXIST";

                } else {

                    $user->name = $request->name;
                    $user->password = Hash::make($request->password);

                    if ($user->save()) {

                        $userSpid = UserSpid::firstOrNew(['user_id' => $user->id]);
                        $userSpid->user_spid_id = $request->user_spid_id;
                        $userSpid->save();

                        $response->status = 200;
                        $response->msg = "";
                        $response->data = ['user' => $user, 'userSpid' => $userSpid];

                    } else {

                        $response->status = 401;
                        $response->msg = "USER_CREATE_FAILED";

                    }

                }

            } catch (\Throwable$th) {

                $response->status = 500;
                $response->msg = $th->getMessage();
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
            $response->msg = "VALIDATION_ERROR";
            $response->data = $validateData->errors();

        } else {

            $userSpid = UserSpid::where('user_spid_id', $request->user_spid_id)->first();

            if ($userSpid) {

                $UserModel = config('auth.providers.users.model');
                $UserModel = new $UserModel;

                if (config('spid.user_profile_relationship')) {
                    $user = $UserModel::with(config('spid.user_profile_relationship'))->where('id', $userSpid->user_id)->first();
                } else {
                    $user = $UserModel::where('id', $userSpid->user_id)->first();
                }

                if ($user) {

                    $response->status = 200;
                    $response->msg = "";
                    $response->data = $user;

                } else {

                    $response->status = 404;
                    $response->msg = "USER_NOT_FOUND";

                }
            } else {

                $response->status = 404;
                $response->msg = "USERSPID_NOT_FOUND";
            }

        }

        return response()->json($response);

    }

    public function check(Request $request)
    {
        $response = new SpidResponse;
        $response->msg = "";

        $validateData = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'user_spid_id' => 'required',
        ]);

        if ($validateData->fails()) {

            $response->status = 401;
            $response->msg = "VALIDATION_ERROR";
            $response->data = $validateData->errors();

        } else {

            try {

                $UserModel = config('auth.providers.users.model');
                $UserModel = new $UserModel;

                $credentials = ['email' => $request->username, 'password' => $request->password];

                if (Auth::guard('web')->attempt($credentials)) {

                    $response->msg .= "AUTHENTICATED-";

                    $user = $UserModel::where('email', $request->username)->first();

                    $userSpid = UserSpid::firstOrNew(['user_id' => $user->id]);

                    if ($userSpid->exists) {

                        $response->status = 409;
                        $response->msg .= "ABORT_ALREADY_BIND-";
                        $response->data = ['user' => $user];

                    } else {

                        $userSpid->user_spid_id = $request->user_spid_id;
                        $userSpid->save();

                        $response->status = 200;
                        $response->msg .= "SUCCESS_BIND-";
                        $response->data = ['user' => $user, 'userSpid' => $userSpid, 'redirect_url' => route(config('spid.redirect_sso'))];

                    }

                } else {

                    $response->status = 409;
                    $response->msg .= "FAILED_CHECK-";

                }

            } catch (\Throwable$th) {
                $response->status = 500;
                $response->msg = $th->getMessage();
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
            $response->msg = "VALIDATION_ERROR";
            $response->data = $validateData->errors();

        } else {

            $userSpid = UserSpid::where('user_id', $request->user_id)->where('user_spid_id', $request->user_spid_id)->first();

            if ($userSpid) {

                $userSpid->generateRedirectToken();

                $response->status = 200;
                $response->msg = "SUCCESS";
                $response->data = [
                    'redirect_token' => $userSpid->redirect_token,
                    'redirect_token_expired_at' => $userSpid->redirect_token_expired_at ? $userSpid->redirect_token_expired_at->format('Y-m-d H:i:s') : null,
                    'redirect_url' => route(config('spid.redirect_sso')),
                ];

            } else {

                $response->status = 404;
                $response->msg = "USERSPID_NOT_FOUND";

            }
        }

        return response()->json($response);
    }
}
