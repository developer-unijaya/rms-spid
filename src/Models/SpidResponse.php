<?php

namespace DeveloperUnijaya\RmsSpid\Models;

class SpidResponse
{
    public $status;
    public $msg;
    public $data;

    public function __construct($status = 200, $msg = [], $data = null)
    {
        $this->status = $status;
        $this->msg = $msg;
        $this->data = $data;
    }
}

// $response = new SpidResponse;
// $response->status = "";
// $response->msg[] = "";
// $response->data = "";
// return response()->json($response);
