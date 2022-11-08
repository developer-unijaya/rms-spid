<?php

namespace DeveloperUnijaya\RmsSpid\Helpers;

use Carbon\Carbon;
use DeveloperUnijaya\RmsSpid\Models\UserSpid;

class SpidHelper
{
    public static function updateProfile($user_id)
    {

    }

    public static function updateRegStatus($user_id, $is_reg_approve = true)
    {
        $userSpid = UserSpid::where('user_id', $user_id)->first();

        if ($userSpid) {

            if ($is_reg_approve) {
                $userSpid->reg_approve_at = Carbon::now();
            } else {
                $userSpid->reg_reject_at = Carbon::now();
            }

            $userSpid->save();
        }

        return $userSpid;
    }
}
