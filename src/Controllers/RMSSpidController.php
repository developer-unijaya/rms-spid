<?php
namespace DeveloperUnijaya\RMSSpid\Controllers;

use DeveloperUnijaya\RMSSpid\RMSSpid;

class RMSSpidController
{
    public function __invoke(RMSSpid $RMSSpid)
    {
        $quote = $RMSSpid->justDoIt();

        return $quote;
    }
}
