<?php

namespace DeveloperUnijaya\RmsSpid\Controllers;

use DeveloperUnijaya\RmsSpid\Models\SpidResponse;
use Illuminate\Http\Request;

class RmsSpidConfigController
{
    public function getConfig(Request $request)
    {
        $response = new SpidResponse;

        $data = [
            'app' => config('app'),
            'auth' => config('auth'),
            'sanctum' => config('sanctum'),
            'session' => config('session'),
        ];

        $response->data = $data;

        return response()->json($response);
    }
}
