<?php

namespace DeveloperUnijaya\RmsSpid\Helpers;

use DeveloperUnijaya\RmsSpid\Models\UserSpid;
use Illuminate\Support\Facades\Http;
use Throwable;

class HttpHelper
{
    public static function sendRegUserSpid(UserSpid $userSpid)
    {
        $success = false;
        $userSpid->appendLog(['sendRegUserSpid START']);

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

            if (config('rms-spid.spid_key')) {
                $data['spid_key'] = config('rms-spid.spid_key');
            }

            $auth_token = HttpHelper::getAuthToken($userSpid);

            $responseCheck = Http::timeout(60)->withToken($auth_token)->post($spid_reg_url, $data);

            if ($responseCheck->successful()) {

                $userSpid->appendLog(['sendRegUserSpid SUCCESSFUL']);

                $responseJson = $responseCheck->json();
                $responseObj = (object) $responseJson;

                if ($responseObj->status == 200) {

                    $userSpid->appendLog(['sendRegUserSpid 200']);

                    $responseData = (object) $responseObj->data;
                    $responseUser = (object) $responseData->user;

                    $userSpid->user_spid_id = $responseUser->spid_id;
                    $userSpid->save();

                    $success = true;

                } else {

                    $userSpid->appendLog(['sendRegUserSpid FAILED', $responseObj->status]);
                }

            } else {
                $userSpid->appendLog(['sendRegUserSpid UNSUCCESSFUL', $responseCheck->body()]);
            }

        } catch (Throwable $th) {

            $userSpid->appendLog(['sendRegUserSpid FAILED', $th->getMessage()]);
        }

        return $success;
    }

    public static function getAuthToken(UserSpid $userSpid)
    {
        $auth_token = null;

        $userSpid->appendLog(['getAuthToken START']);
        try {

            $loginUrl = config('rms-spid.spid_base_url') . '/api/v1/auth/login';

            $data = [
                'username' => config('rms-spid.spid_username'),
                'password' => config('rms-spid.spid_password'),
            ];

            if (config('rms-spid.spid_key')) {
                $data['spid_key'] = config('rms-spid.spid_key');
            }

            $responseCheck = Http::timeout(60)->post($loginUrl, $data);

            if ($responseCheck->successful()) {

                $userSpid->appendLog(['getAuthToken SUCCESSFUL']);

                $responseJson = $responseCheck->json();
                $responseObj = (object) $responseJson;

                if ($responseObj->status == 200) {

                    $responseData = (object) $responseObj->data;
                    $auth_token = $responseData->auth_token;
                }

            } else {
                $userSpid->appendLog(['getAuthToken FAILED', $responseCheck->body()]);
            }

        } catch (Throwable $th) {

            $userSpid->appendLog(['getAuthToken FAILED', $th->getMessage()]);

        }

        return $auth_token;
    }
}
