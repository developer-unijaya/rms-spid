<?php

namespace DeveloperUnijaya\RmsSpid\Models;

use DeveloperUnijaya\RmsSpid\Models\SpidResponse;
use Illuminate\Http\Request;

class SpidKey
{
    public static function check(Request $request)
    {
        $response = new SpidResponse;

        if (config('rms-spid.spid_key')) {

            $req_spid_key = $request->req_spid_key;

            // dd('req_spid_key', $req_spid_key);

            if ($req_spid_key) {
                // dd($req_spid_key, 'ade');

                if ($req_spid_key !== config('rms-spid.spid_key')) {

                    // dd(config('rms-spid.spid_key'), $request);

                    // $response->status = 403;
                    // $response->msg = "SPID_KEY_INVALID";
                    // return response()->json($response);
                }

            } else {

                // dd($req_spid_key, 'xde');

                // $response->status = 403;
                // $response->msg = "SPID_KEY_NOT_FOUND";
                // return response()->json($response);

            }

        }
    }
}
