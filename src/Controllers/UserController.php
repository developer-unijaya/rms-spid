<?php

namespace DeveloperUnijaya\RmsSpid\Controllers;

use DeveloperUnijaya\RmsSpid\Models\EpsCompany;
use DeveloperUnijaya\RmsSpid\Models\EpsCompanyUser;
use DeveloperUnijaya\RmsSpid\Models\PprnCompany;
use DeveloperUnijaya\RmsSpid\Models\PprnProfile;
use DeveloperUnijaya\RmsSpid\Models\SpidResponse;
use DeveloperUnijaya\RmsSpid\Models\UserSpid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;

class UserController
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
            'reg_type' => ['required'],
        ]);

        if ($validateData->fails()) {

            $response->status = 422;
            $response->message[] = "VALIDATION_ERROR";
            $response->message[] = json_encode($validateData->errors());
            $response->data = $validateData->errors();

        } else {

            $response->message[] = "VALIDATION_OK";

            DB::beginTransaction();
            try {

                $UserModel = config('auth.providers.users.model');
                $UserModel = new $UserModel;

                $user = $UserModel::firstOrNew(['email' => $request->email]);

                if ($user->exists) {

                    $response->status = 401;
                    $response->message[] = "USER_ALREADY_EXIST";

                } else {

                    $response->message[] = "USER_NEW";

                    $userDatas = $request->userDatas;
                    foreach ($userDatas as $key => $userData) {
                        $user[$key] = $userData;
                    }

                    $user['password'] = Hash::make($request->password);

                    if ($user->save()) {

                        $response->message[] = "USER_SAVE_SUCCESS";

                        // PPRN Profile & Company
                        if (in_array($request->reg_type, ['int_pprn', 'researcher_pprn', 'company_pprn'])) {

                            $profile = new PprnProfile;
                            $profile->user_id = $user->id;
                            $profile->save();

                            if (in_array($request->reg_type, ['company_pprn'])) {

                                $company = new PprnCompany;
                                $company->pic_id = $user->id;

                                $companyDatas = $request->companyDatas;
                                foreach ($companyDatas as $key => $companyData) {
                                    $company[$key] = $companyData;
                                }

                                $company->save();
                            }
                        }

                        // EPS Company
                        if (in_array($request->reg_type, ['company_eps'])) {

                            $company = new EpsCompany;

                            $companyDatas = $request->companyDatas;
                            foreach ($companyDatas as $key => $companyData) {
                                $company[$key] = $companyData;
                            }

                            if ($company->save()) {

                                $companyUser = new EpsCompanyUser;

                                $companyUser->consultancy_company_id = $company->id;
                                $companyUser->users_id = $user->id;

                                $companyUser->save();
                            }
                        }

                        $userSpid = UserSpid::firstOrNew(['user_id' => $user->id]);
                        $userSpid->src = 'from_spid_reg';
                        $userSpid->user_spid_id = $request->user_spid_id;
                        $userSpid->reg_type = $request->reg_type;
                        $userSpid->reg_json = json_encode($request->except(['spid_key', 'password']));

                        if ($userSpid->save()) {

                            DB::commit();
                            $response->status = 200;
                            $response->message[] = "USERSPID_SAVE_SUCCESS";
                            $response->message[] = "SUCCESS";
                            $response->data = ['user' => $user, 'userSpid' => $userSpid];
                        } else {

                            DB::rollBack();
                            $response->status = 401;
                            $response->message[] = "USERSPID_SAVE_FAILED";
                        }

                    } else {

                        DB::rollBack();
                        $response->status = 401;
                        $response->message[] = "USER_SAVE_FAILED";
                    }

                }

            } catch (Throwable $th) {

                DB::rollBack();
                $response->status = 500;
                $response->message[] = "ERROR";
                $response->message[] = $th->getMessage();
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

            $response->status = 422;
            $response->message[] = "VALIDATION_ERROR";
            $response->message[] = json_encode($validateData->errors());
            $response->data = $validateData->errors();

        } else {

            $response->message[] = "VALIDATION_OK";

            $userSpid = UserSpid::where('user_spid_id', $request->user_spid_id)->first();

            if ($userSpid) {

                $response->message[] = "USERSPID_FOUND";

                $UserModel = config('auth.providers.users.model');
                $UserModel = new $UserModel;

                if (config('rms-spid.user_profile_relationship')) {

                    $response->message[] = "CONFIG_USER_PROFILE_RELATIONSHIP_EXIST";
                    $user = $UserModel::with(config('rms-spid.user_profile_relationship'))->where('id', $userSpid->user_id)->first();
                } else {

                    $response->message[] = "CONFIG_USER_PROFILE_RELATIONSHIP_DOES_NOT_EXIST";
                    $user = $UserModel::where('id', $userSpid->user_id)->first();
                }

                if ($user) {

                    $response->status = 200;
                    $response->message[] = "USER_FOUND";
                    $response->message[] = "SUCCESS";
                    $response->data = ['user' => $user, 'userSpid' => $userSpid];

                } else {

                    $response->status = 404;
                    $response->message[] = "USER_NOT_FOUND";

                }
            } else {

                $response->status = 404;
                $response->message[] = "USERSPID_NOT_FOUND";
            }

        }

        return response()->json($response);
    }

    public function updateSpidId(Request $request)
    {
        $response = new SpidResponse;

        $validateData = Validator::make($request->all(), [
            'user_id' => ['required'],
            'new_user_spid_id' => ['required'],
        ]);

        if ($validateData->fails()) {

            $response->status = 422;
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

                    $response->message[] = "USER_FOUND";

                    $userSpid = UserSpid::firstOrNew(['user_id' => $user->id]);
                    // $userSpid->src = 'from_spid_reg';
                    $userSpid->user_spid_id = $request->new_user_spid_id;

                    if ($userSpid->save()) {

                        $response->status = 200;
                        $response->message[] = "USERSPID_SAVE_SUCCESS";
                        $response->message[] = "SUCCESS";
                        $response->data = ['user' => $user, 'userSpid' => $userSpid];

                    } else {

                        $response->message[] = "USERSPID_SAVE_FAILED";
                    }

                } else {

                    $response->status = 404;
                    $response->message[] = "USER_NOT_FOUND";

                }

            } catch (Throwable $th) {

                $response->status = 500;
                $response->message[] = "ERROR";
                $response->message[] = $th->getMessage();

            }

        }

        return response()->json($response);
    }

    public function check(Request $request)
    {
        $response = new SpidResponse;

        $validateData = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'user_spid_id' => 'required',
        ]);

        if ($validateData->fails()) {

            $response->status = 422;
            $response->message[] = "VALIDATION_ERROR";
            $response->message[] = json_encode($validateData->errors());
            $response->data = $validateData->errors();

        } else {

            $response->message[] = "VALIDATION_OK";

            try {

                $UserModel = config('auth.providers.users.model');
                $UserModel = new $UserModel;

                $credentials = ['email' => $request->username, 'password' => $request->password];

                if (Auth::guard('web')->attempt($credentials)) {

                    $response->message[] = "ATTEMPT_CRED_SUCCESS";

                    $user = $UserModel::where('email', $request->username)->first();

                    $userSpid = UserSpid::firstOrNew(['user_id' => $user->id]);
                    $userSpid->src = 'from_spid_bind';

                    if ($userSpid->exists && $userSpid->user_spid_id) {

                        $response->status = 409;
                        $response->message[] = "ABORT_ALREADY_BIND";
                        $response->data = ['user' => $user];

                    } else {

                        $userSpid->user_spid_id = $request->user_spid_id;

                        if ($userSpid->save()) {

                            $response->status = 200;
                            $response->message[] = "SUCCESS_BIND";
                            $response->data = ['user' => $user, 'userSpid' => $userSpid];

                        } else {

                            $response->status = 401;
                            $response->message[] = "USERSPID_SAVE_FAILED";
                        }
                    }

                } else {

                    $response->status = 409;
                    $response->message[] = "ATTEMPT_CRED_FAILED";
                }

            } catch (Throwable $th) {

                $response->status = 500;
                $response->message[] = "ERROR";
                $response->message[] = $th->getMessage();
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

            $response->status = 422;
            $response->message[] = "VALIDATION_ERROR";
            $response->message[] = json_encode($validateData->errors());
            $response->data = $validateData->errors();

        } else {

            $response->message[] = "VALIDATION_OK";

            $userSpid = UserSpid::where('user_id', $request->user_id)->where('user_spid_id', $request->user_spid_id)->first();

            if ($userSpid) {

                $response->message[] = "USERSPID_FOUND";

                $tokenSuccess = $userSpid->generateRedirectToken();

                if ($tokenSuccess) {

                    $response->message[] = "REDIRECT_TOKEN_GENERATE_SUCCESS";
                } else {

                    $response->message[] = "REDIRECT_TOKEN_GENERATE_FAILED";
                }

                $response->status = 200;
                $response->message[] = "SUCCESS";
                $response->data = [
                    'redirect_token' => $userSpid->redirect_token,
                    'redirect_token_expired_at' => $userSpid->redirect_token_expired_at ? $userSpid->redirect_token_expired_at->format('Y-m-d H:i:s') : null,
                    'redirect_url' => route('spid.sso.auth'),
                ];

            } else {

                $response->status = 404;
                $response->message[] = "USERSPID_NOT_FOUND";
            }
        }

        return response()->json($response);
    }
}
