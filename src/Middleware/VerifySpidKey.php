<?php

namespace DeveloperUnijaya\RmsSpid\Middleware;

use Closure;
use DeveloperUnijaya\RmsSpid\Models\SpidResponse;

class VerifySpidKey
{
    public function handle($request, Closure $next)
    {
        $response = new SpidResponse;

        if (config('rms-spid.spid_key')) {

            $response->msg[] = "SPID_KEY_CHECK";

            $spid_key = $request->spid_key;

            if ($spid_key) {

                $response->msg[] = "SPID_KEY_FOUND";

                if ($spid_key !== config('rms-spid.spid_key')) {

                    $response->status = 403;
                    $response->msg[] = "SPID_KEY_INVALID";
                    return response()->json($response);
                }

            } else {

                $response->status = 403;
                $response->msg[] = "SPID_KEY_NOT_FOUND";
                return response()->json($response);

            }
        }

        return $next($request);
    }
}
