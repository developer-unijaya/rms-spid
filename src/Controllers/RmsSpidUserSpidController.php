<?php

namespace DeveloperUnijaya\RmsSpid\Controllers;

use DeveloperUnijaya\RmsSpid\Models\SpidResponse;
use DeveloperUnijaya\RmsSpid\Models\UserSpid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RmsSpidUserSpidController
{
    public function index()
    {
        $response = new SpidResponse;

        try {

            $userSpids = UserSpid::get();

            $response->status = 200;
            $response->msg = "SUCCESS";

            $response->data = $userSpids;

        } catch (\Throwable$th) {

            $response->status = 500;
            $response->msg = $th->getMessage();
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

            $response->status = 401;
            $response->msg = "VALIDATION_ERROR";
            $response->data = $validateData->errors();

        } else {

            try {

                $UserModel = config('auth.providers.users.model');
                $UserModel = new $UserModel;

                $user = $UserModel::where('id', $request->user_id)->first();

                if ($user) {

                    $userSpid = UserSpid::firstOrNew(['user_id' => $user->id]);

                    $isExist = false;
                    if ($userSpid->exists) {
                        $isExist = true;
                    }

                    $userSpid->user_spid_id = $request->user_spid_id;
                    $userSpid->save();

                    $response->status = 200;
                    $response->msg = "";
                    $response->data = ['user' => $user, 'userSpid' => $userSpid, 'isExist' => $isExist];

                } else {

                    $response->status = 401;
                    $response->msg = "USER_DOES_NOT_EXIST";

                }

            } catch (\Throwable$th) {

                $response->status = 500;
                $response->msg = $th->getMessage();
            }

        }

        return response()->json($response);
    }

    public function show(Request $request, $id)
    {
        $response = new SpidResponse;

        $userSpid = UserSpid::where('id', $id)->first();

        if ($userSpid) {

            $response->status = 200;
            $response->msg = "SUCCESS";
            $response->data = $userSpid;

        } else {

            $response->status = 401;
            $response->msg = "USERSPID_DOES_NOT_EXIST";

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
            
            $response->data = $userSpid;

        } else {

            $response->status = 401;
            $response->msg = "USERSPID_DOES_NOT_EXIST";
        }

        return response()->json($response);
    }

    public function destroy(Request $request, $id)
    {
        $response = new SpidResponse;

        $userSpid = UserSpid::where('id', $id)->first();

        if ($userSpid) {

            $tempData = $userSpid;

            $userSpid->delete();
            
            $response->status = 200;
            $response->msg = 'SUCCESS';
            $response->data = $tempData;

        } else {

            $response->status = 401;
            $response->msg = "USERSPID_DOES_NOT_EXIST";
        }

        return response()->json($response);
    }

}
