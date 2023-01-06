<?php

namespace DeveloperUnijaya\RmsSpid\Controllers;

use Carbon\Carbon;
use DeveloperUnijaya\RmsSpid\Models\SpidResponse;
use DeveloperUnijaya\RmsSpid\Models\UserSpid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SsoController
{
    public function spidTest()
    {
        echo "eRMS-SPID";
    }

    public function ssoAuth(Request $request)
    {
        $response = new SpidResponse;

        $validateData = Validator::make($request->all(), [
            'user_spid_id' => ['required'],
            'redirect_token' => ['required'],
        ]);

        if ($validateData->fails()) {

            $response->status = 422;
            $response->message[] = "VALIDATION_ERROR";
            $response->message[] = json_encode($validateData->errors());
            $response->data = $validateData->errors();

        } else {

            $response->message[] = "VALIDATION_OK";

            $userSpid = UserSpid::where('user_spid_id', $request->user_spid_id)->where('redirect_token', $request->redirect_token)->first();

            if ($userSpid) {

                $response->message[] = "USERSPID_FOUND";

                if ($userSpid->userActive()) {

                    // To check if the token has Expiry Timestamp
                    if ($userSpid->redirect_token_expired_at) {

                        $response->message[] = "REDIRECT_TOKEN_EXPIRED_AT_EXIST";
                        $now = Carbon::now(config('rms-spid.timezone'));

                        // Check Token Validity
                        if ($now->gt($userSpid->redirect_token_expired_at)) {

                            $response->message[] = "REDIRECT_TOKEN_EXPIRED";

                            return redirect()->route(config('rms-spid.redirect_sso_failed'), ['failed_msg' => 'TOKEN_EXPIRED']);
                        } else {

                            $response->message[] = "REDIRECT_TOKEN_DOES_NOT_EXPIRED";
                        }
                    } else {

                        $response->message[] = "REDIRECT_TOKEN_EXPIRED_AT_DOES_NOT_EXIST";
                    }

                    $UserModel = config('auth.providers.users.model');
                    $UserModel = new $UserModel;

                    $user = $UserModel::where('id', $userSpid->user_id)->first();
                    if ($user) {

                        $response->message[] = "USER_FOUND";
                        $response->message[] = "SUCCESS";
                        return redirect()->route('spid.sso.auth.login', ['user_spid_id' => $request->user_spid_id, 'redirect_token' => $request->redirect_token]);

                    } else {

                        $response->status = 404;
                        $response->message[] = "USER_NOT_FOUND";
                        return redirect()->route(config('rms-spid.redirect_sso_failed'), ['failed_msg' => 'USER_NOT_FOUND']);
                    }
                } else {

                    $response->status = 401;
                    $response->message[] = "USER_INACTIVE_OR_NOT_EXIST";
                    return redirect()->route(config('rms-spid.redirect_sso_failed'), ['failed_msg' => 'USER_INACTIVE_OR_NOT_EXIST']);
                }

            } else {

                $response->status = 404;
                $response->message[] = "USERSPID_NOT_FOUND";
                return redirect()->route(config('rms-spid.redirect_sso_failed'), ['failed_msg' => 'USERSPID_NOT_FOUND']);
            }
        }

        return response()->json($response, $response->status);
    }

    public function ssoLogin(Request $request)
    {
        $userSpid = UserSpid::where('user_spid_id', $request->user_spid_id)->where('redirect_token', $request->redirect_token)->first();

        if (config('rms-spid.redirect_token_once')) {
            $userSpid->resetRedirectToken();
        }

        if (Auth::check()) {
            Auth::logout();
        }

        Auth::guard('web')->loginUsingId($userSpid->user_id);

        return redirect()->intended(config('rms-spid.redirect_sso_success'));
    }

    public function ssoFailed(Request $request)
    {
        $failed_msg = $request->failed_msg;

        return view('RmsSpidView::ssoAuthFailed', compact('failed_msg'));
    }
}
