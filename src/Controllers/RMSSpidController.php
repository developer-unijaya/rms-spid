<?php
namespace DeveloperUnijaya\RMSSpid\Controllers;

use DeveloperUnijaya\RMSSpid\RMSSpid;

class InspirationController
{
    public function __invoke(RMSSpid $RMSSpid)
    {
        $quote = $RMSSpid->justDoIt();

        return $quote;
    }
}
