<?php

namespace DeveloperUnijaya\RmsSpid\Helpers;

use DeveloperUnijaya\RmsSpid\Models\UserSpid;
use Illuminate\Support\Facades\Http;

class HttpHelper
{
    public static function getAuthToken()
    {
        $auth_token = null;

        try {

            $loginUrl = config('rms-spid.spid_base_url') . '/api/v1/auth/login';

            $data = [
                'spid_key' => config('rms-spid.spid_key'),
                'username' => config('rms-spid.spid_username'),
                'password' => config('rms-spid.spid_password'),
            ];

            $responseCheck = Http::timeout(60)->post($loginUrl, $data);

            if ($responseCheck->successful()) {

                $responseJson = $responseCheck->json();
                $responseObj = (object) $responseJson;

                if ($responseObj->status == 200) {

                    $responseData = (object) $responseObj->data;
                    $auth_token = $responseData->auth_token;

                    var_dump($auth_token);

                }

            } else {
                dd("FAILED", $responseCheck->body());
            }

        } catch (Throwable $th) {

            dd("FAILED", $th->getMessage());

        }

        return $auth_token;
    }

    public static function sendRegUserSpid(UserSpid $userSpid)
    {
        $success = false;

        try {

            $UserModel = config('auth.providers.users.model');
            $UserModel = new $UserModel;

            $user = $UserModel::where('id', $userSpid->user_id)->first();

            $spid_reg_url = config('rms-spid.spid_base_url') . '/api/v1/user/register';

            $data = [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];

            $auth_token = HttpHelper::getAuthToken();

            $responseCheck = Http::timeout(60)->withToken($auth_token)->post($spid_reg_url, $data);

            if ($responseCheck->successful()) {

                $responseJson = $responseCheck->json();
                $responseObj = (object) $responseJson;

                if ($responseObj->status == 200) {

                    $responseData = (object) $responseObj->data;

                    dd($responseData);

                    $responseUser = (object) $responseData->user;

                    $userSpid->user_spid_id = $responseUser->spid_id;
                    $userSpid->save();

                    $success = true;

                } else {
                    dd("FAILED", $responseObj->status);
                }

            } else {
                dd("FAILED", $responseCheck->body());
            }

        } catch (Throwable $th) {

            dd("FAILED", $th->getMessage());
        }

        return $success;
    }
}
