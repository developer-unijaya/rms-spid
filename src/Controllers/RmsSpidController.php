<?php

namespace DeveloperUnijaya\RmsSpid\Controllers;

// use DeveloperUnijaya\RmsSpid\Models\User;
use App\Models\User;
use DeveloperUnijaya\RmsSpid\Models\UserSpidToken;
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
        $validate = Validator::make($request->all(), [
            'user_id' => ['required'],
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

        $userSpidToken = UserSpidToken::where('user_id', $request->user_id)->where('spid_id', $request->user_spid_id)->where('redirect_token', $request->redirect_token)->first();

        if ($userSpidToken) {

            $user = User::where('id', $userSpidToken->user_id)->first();

            if ($user) {

                if (Auth::check()) {
                    Auth::logout();
                }

                Auth::guard('web')->login($user);

            } else {
                return redirect()->back();
            }

            return redirect('/home');
        } else {
            dd("UNAUTHORIZED");
        }
    }
}
