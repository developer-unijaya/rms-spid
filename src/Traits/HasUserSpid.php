<?php

namespace DeveloperUnijaya\RmsSpid\Traits;

use Carbon\Carbon;
use DeveloperUnijaya\RmsSpid\Models\UserSpid;

trait HasUserSpid
{
    public function userSpid()
    {
        return $this->hasOne('DeveloperUnijaya\RmsSpid\Models\UserSpid', 'user_id');
    }

    public function approveSpidReg()
    {
        $userSpid = $this->userSpid;
        if ($userSpid) {
            $userSpid->reg_approve_at = Carbon::now();
            $userSpid->reg_reject_at = null;
            $userSpid->save();
        }

        return $userSpid;
    }

    public function rejectSpidReg()
    {
        $userSpid = $this->userSpid;
        if ($userSpid) {
            $userSpid->reg_approve_at = null;
            $userSpid->reg_reject_at = Carbon::now();
            $userSpid->save();
        }

        return $userSpid;
    }
}
