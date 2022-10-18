<?php

namespace DeveloperUnijaya\RmsSpid\Controllers;

use DeveloperUnijaya\RmsSpid\Models\SpidResponse;
use Illuminate\Http\Request;

class RmsSpidConfigController
{
    // ! For Dev & Debug purpose
    public function getConfig(Request $request)
    {
        $response = new SpidResponse;

        try {

            $data = [
                'rms-spid' => config('rms-spid'),
                'app' => config('app'),
                'auth' => config('auth'),
                'sanctum' => config('sanctum'),
                'session' => config('session'),
            ];

            $response->data = $data;
            $response->msg[] = "SUCCESS";

        } catch (\Throwable$th) {

            $response->msg[] = "ERROR";
            $response->status = 500;
            $response->msg[] = $th->getMessage();

        }

        return response()->json($response);
    }
}
