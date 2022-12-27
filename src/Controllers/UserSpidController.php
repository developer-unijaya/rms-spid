<?php

namespace DeveloperUnijaya\RmsSpid\Controllers;

use DeveloperUnijaya\RmsSpid\Models\SpidResponse;
use DeveloperUnijaya\RmsSpid\Models\UserSpid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class UserSpidController
{
    public function index()
    {
        $response = new SpidResponse;

        try {

            $userSpids = UserSpid::with('user')->orderBy('id')->get();

            $response->status = 200;
            $response->data = $userSpids;
            $response->message[] = "SUCCESS";

        } catch (Throwable $th) {

            $response->status = 500;
            $response->message[] = 'ERROR';
            $response->message[] = $th->getMessage();
        }

        return response()->json($response);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $response = new SpidResponse;

        $validateData = Validator::make($request->all(), [
            'user_id' => ['required'],
            'user_spid_id' => ['required'],
        ]);

        if ($validateData->fails()) {

            $response->status = 400;
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

                    $response->message[] = "USER_EXIST";

                    $userSpid = UserSpid::firstOrNew(['user_id' => $user->id]);

                    $isExist = null;
                    if ($userSpid->exists) {

                        $response->message[] = "USERSPID_EXIST";
                        $isExist = true;
                    } else {

                        $response->message[] = "USERSPID_DOES_NOT_EXIST";
                        $isExist = false;
                    }

                    $userSpid->user_spid_id = $request->user_spid_id;
                    $userSpid->save();

                    $response->status = 200;
                    $response->data = ['user' => $user, 'userSpid' => $userSpid, 'isExist' => $isExist];
                    $response->message[] = "SUCCESS";

                } else {

                    $response->status = 401;
                    $response->message[] = "USER_DOES_NOT_EXIST";
                }

            } catch (Throwable $th) {

                $response->status = 500;
                $response->message[] = 'ERROR';
                $response->message[] = $th->getMessage();
            }

        }

        return response()->json($response);
    }

    public function show(Request $request, $id)
    {
        $response = new SpidResponse;

        $userSpid = UserSpid::where('id', $id)->with('user')->first();

        if ($userSpid) {

            $response->status = 200;
            $response->message[] = "USERSPID_EXIST";

            $response->message[] = "SUCCESS";
            $response->data = $userSpid;

        } else {

            $response->status = 401;
            $response->message[] = "USERSPID_DOES_NOT_EXIST";
        }

        return response()->json($response);
    }

    public function edit(Request $request, $id)
    {
    }

    public function update(Request $request, $id)
    {
        $response = new SpidResponse;

        $userSpid = UserSpid::where('id', $id)->first();

        if ($userSpid) {

            $response->message[] = "USERSPID_EXIST";

            try {

                if ($request->user_id) {
                    $userSpid->user_id = $request->user_id;
                }

                if ($request->user_spid_id) {
                    $userSpid->user_spid_id = $request->user_spid_id;
                }

                if ($request->redirect_token) {
                    $userSpid->redirect_token = $request->redirect_token;
                }

                $userSpid->save();

                $response->status = 200;
                $response->message[] = 'SUCCESS';
                $response->data = $userSpid;

            } catch (Throwable $th) {

                $response->status = 500;
                $response->message[] = 'ERROR';
                $response->message[] = $th->getMessage();

            }

        } else {

            $response->status = 401;
            $response->message[] = "USERSPID_DOES_NOT_EXIST";
        }

        return response()->json($response);
    }

    public function destroy(Request $request, $id)
    {
        $response = new SpidResponse;

        $userSpid = UserSpid::where('id', $id)->first();

        if ($userSpid) {

            $tempData = $userSpid;

            try {

                $userSpid->delete();

                $response->status = 200;
                $response->message[] = 'SUCCESS';
                $response->data = $tempData;

            } catch (Throwable $th) {

                $response->status = 500;
                $response->message[] = 'ERROR';
                $response->message[] = $th->getMessage();
            }

        } else {

            $response->status = 401;
            $response->message[] = "USERSPID_DOES_NOT_EXIST";
        }

        return response()->json($response);
    }

}
