<?php

namespace DeveloperUnijaya\RmsSpid\Controllers;

// use DeveloperUnijaya\RmsSpid\Models\User;
use App\Models\User;
use DeveloperUnijaya\RmsSpid\Models\UserSpid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RmsSpidController
{
    public function testSpid()
    {
        echo "eRMS-SPID";
    }

    public function ssoAuth(Request $request)
    {
        // Validation/Log or anything here. Before Logged In
        $validate = Validator::make($request->all(), [
            'user_spid_id' => ['required'],
            'redirect_token' => ['required'],
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validate->errors(),
            ], 401);
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
            // return redirect()->back();
        }
    }

    public function ssoLogin(Request $request)
    {
        $userSpid = UserSpid::where('user_spid_id', $request->user_spid_id)->where('redirect_token', $request->redirect_token)->first();
        // $userSpid->redirect_token = null;
        // $userSpid->save();

        Auth::guard('web')->loginUsingId($userSpid->user_id);

        return redirect()->intended('home');
    }
}
