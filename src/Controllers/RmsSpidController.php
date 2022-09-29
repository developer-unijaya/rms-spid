<?php

namespace DeveloperUnijaya\RmsSpid\Controllers;

// use DeveloperUnijaya\RmsSpid\Models\User;
use App\Models\User;
use DeveloperUnijaya\RmsSpid\Models\UserSpid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DeveloperUnijaya\RmsSpid\Models\SpidResponse;

class RmsSpidController
{
    public function testSpid()
    {
        echo "eRMS-SPID";
    }

    public function ssoAuth(Request $request)
    {
        $response = new SpidResponse;

        // Validation/Log or anything here. Before Logged In
        $validateData = Validator::make($request->all(), [
            'user_spid_id' => ['required'],
            'redirect_token' => ['required'],
        ]);

        if ($validateData->fails()) {

            $response->status = 401;
            $response->msg = "VALIDATION_ERROR";
            $response->data = $validateData->errors();
        }

        $userSpid = UserSpid::where('user_spid_id', $request->user_spid_id)->where('redirect_token', $request->redirect_token)->first();

        if ($userSpid) {

            $user = User::where('id', $userSpid->user_id)->first();

            if ($user) {

                if (Auth::check()) {
                    Auth::logout();
                }

                Auth::guard('web')->loginUsingId($userSpid->user_id);

                // Login to Sub system
                return redirect()->route('spid.sso.login', ['user_spid_id' => $request->user_spid_id, 'redirect_token' => $request->redirect_token]);

            } else {
                return redirect()->back();
            }

        } else {
            dd("UNAUTHORIZED");
        }
    }

    public function ssoLogin(Request $request)
    {
        $userSpid = UserSpid::where('user_spid_id', $request->user_spid_id)->where('redirect_token', $request->redirect_token)->first();

        if (config('spid.strict_redirect_token')) {
            $userSpid->redirect_token = null;
            $userSpid->save();
        }

        Auth::guard('web')->loginUsingId($userSpid->user_id);

        return redirect()->intended('home');
    }
}
