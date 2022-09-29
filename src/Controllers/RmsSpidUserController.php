<?php

namespace DeveloperUnijaya\RmsSpid\Controllers;

use DeveloperUnijaya\RmsSpid\Models\SpidResponse;
use DeveloperUnijaya\RmsSpid\Models\User;
use DeveloperUnijaya\RmsSpid\Models\UserSpid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RmsSpidUserController
{
    public function register(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'user_id' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'roles' => ['required'],
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'VALIDATION_ERROR',
                'errors' => $validateData->errors(),
            ], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['user' => $user]);
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

                        $response->status = 200;
                        $response->msg .= "ABORT_ALREADY_BIND-";
                        $response->data = ['user' => $user, 'userSpid' => $userSpid];

                    } else {

                        $userSpid->user_spid_id = $request->user_spid_id;
                        $userSpid->redirect_token = Str::uuid()->toString();
                        $userSpid->save();

                        $response->status = 200;
                        $response->msg .= "SUCCESS_BIND-";
                        $response->data = ['user' => $user, 'userSpid' => $userSpid, 'redirect_url' => route(config('spid.redirect_sso'))];

                    }

                } else {

                    $response->status = 200;
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

                $userSpid->redirect_token = Str::uuid()->toString();
                $userSpid->save();

                $response->status = 200;
                $response->msg = "SUCCESS";
                $response->data = ['redirect_token' => $userSpid->redirect_token, 'redirect_url' => route(config('spid.redirect_sso'))];

            } else {

                $response->status = 200;
                $response->msg = "FAILED";

            }
        }

        return response()->json($response);
    }
}
