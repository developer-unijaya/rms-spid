<?php

namespace DeveloperUnijaya\RmsSpid\Commands;

use Illuminate\Console\Command;
use DeveloperUnijaya\RmsSpid\Models\UserSpid;
use Carbon\Carbon;
class RmsSpidCommand extends Command
{
    public $signature = 'spid:reset-expired-token';

    public $description = 'Reset all user expired redirect token';

    public function handle(): int
    {

        $now = Carbon::now();

        $userSpids = UserSpid::where('redirect_token_expired_at', '<=', $now)->get();

        foreach($userSpids as $userSpid)
        {
            $userSpid->redirect_token = null;
            $userSpid->redirect_token_expired_at = null;
            $userSpid->save();
        }

        $this->comment('completed');

        return self::SUCCESS;
    }
}
