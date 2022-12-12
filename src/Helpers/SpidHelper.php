<?php

namespace DeveloperUnijaya\RmsSpid\Helpers;

use Carbon\Carbon;
use DeveloperUnijaya\RmsSpid\Models\UserSpid;
use DeveloperUnijaya\RmsSpid\Helpers\HttpHelper;

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

                $userSpid->reg_approve_at = Carbon::now(config('rms-spid.timezone'));
                $userSpid->reg_reject_at = null;
            } else {

                $userSpid->reg_approve_at = null;
                $userSpid->reg_reject_at = Carbon::now(config('rms-spid.timezone'));
            }

            $userSpid->save();
        }

        return $userSpid;
    }

    public static function regUserSpid($user_id)
    {
        $userSpid = UserSpid::firstOrNew(['user_id' => $user_id]);
        $userSpid->src = 'from_subsystem_reg';
        $userSpid->save();

        HttpHelper::sendRegUserSpid($userSpid);

        return $userSpid;
    }
}
