<?php

namespace DeveloperUnijaya\RmsSpid\Commands;

use Carbon\Carbon;
use DeveloperUnijaya\RmsSpid\Models\UserSpid;
use Illuminate\Console\Command;

class SpidResetExpiredTokenCommand extends Command
{
    public $signature = 'spid:reset-expired-token';

    public $description = 'Reset all user expired redirect token';

    public function handle(): int
    {
        $now = Carbon::now();

        $userSpids = UserSpid::where('redirect_token_expired_at', '<=', $now)->get();
        $count = count($userSpids);

        foreach ($userSpids as $userSpid) {
            $userSpid->redirect_token = null;
            $userSpid->redirect_token_expired_at = null;
            $userSpid->save();
        }

        $this->comment($count . ' redirect_token resetted');

        return self::SUCCESS;
    }
}
