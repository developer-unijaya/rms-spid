<?php

namespace DeveloperUnijaya\RMSSpid;

use Illuminate\Support\Facades\Http;

class RMSSpid
{
    public function justDoIt()
    {
        $response = Http::get('https://inspiration.goprogram.ai/');

        return $response['quote'] . ' -' . $response['author'];
    }
}
