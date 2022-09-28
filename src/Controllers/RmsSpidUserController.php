<?php

namespace DeveloperUnijaya\RmsSpid\Controllers;

use DeveloperUnijaya\RmsSpid\Models\User;
// use App\Models\User;
use DeveloperUnijaya\RmsSpid\Models\UserSpidToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RmsSpidUserController
{
    public function register(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'user_id' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'roles' => ['required'],
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors(),
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
        $validate = Validator::make($request->all(), [
            'user_spid_id' => ['required'],
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validate->errors(),
            ], 401);
        }

        $user = User::where('spid_id', $request->user_spid_id)->first();

        if ($user) {
            return response()->json([
                'status' => true,
                'message' => '',
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => '',
                'user' => $user,
            ], 404);
        }
    }

    public function redirect(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_spid_id' => ['required'],
            'user_id' => ['required'],
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validate->errors(),
            ], 401);
        }

        $user = User::where('id', $request->user_id)->where('spid_id', $request->user_spid_id)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'user not found',
                'errors' => $validate->errors(),
            ], 404);
        }

        // Untuk subsystem nnt, kne tambah subsystem id
        $userSpidToken = UserSpidToken::firstOrNew(['user_id' => $user->id]);
        $userSpidToken->spid_id = $user->spid_id;
        $userSpidToken->redirect_token = Str::uuid()->toString();

        $userSpidToken->save();

        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $userSpidToken,
        ], 200);

    }
}
