<?php

namespace DeveloperUnijaya\RmsSpid;

use Illuminate\Support\Facades\Http;

class RmsSpid
{
    public function justDoIt()
    {
        $response = Http::get('https://inspiration.goprogram.ai/');

        return $response['quote'] . ' -' . $response['author'];
    }
}
