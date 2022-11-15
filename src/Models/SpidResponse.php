<?php

namespace DeveloperUnijaya\RmsSpid\Models;

class SpidResponse
{
    public $status;
    public $message;
    public $data;

    public function __construct($status = 200, $message = [], $data = null)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }
}

// $response = new SpidResponse;
// $response->status = "";
// $response->message[] = "";
// $response->data = "";
// return response()->json($response);
