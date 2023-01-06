<?php

namespace DeveloperUnijaya\RmsSpid\Controllers;

use Carbon\Carbon;
use DeveloperUnijaya\RmsSpid\Models\SpidResponse;
use Illuminate\Http\Request;
use Throwable;

class ConfigController
{
    // For Dev & Debug purpose
    // Remove Later
    public function getConfig(Request $request)
    {
        $response = new SpidResponse;

        try {

            $data = [
                'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
                'timezone' => Carbon::now()->tz(),
                'rms-spid' => config('rms-spid'),
                'app' => config('app'),
                'auth' => config('auth'),
                'sanctum' => config('sanctum'),
                'session' => config('session'),
            ];

            $response->data = $data;
            $response->message[] = "SUCCESS";

        } catch (Throwable $th) {

            $response->status = 500;
            $response->message[] = "ERROR";
            $response->message[] = $th->getMessage();

        }

        return response()->json($response, $response->status);
    }
}
