<?php

namespace DeveloperUnijaya\RmsSpid\Commands;

use DeveloperUnijaya\RmsSpid\Models\UserSpid;
use Illuminate\Console\Command;

class SpidClearLogCommand extends Command
{
    public $signature = 'spid:clear-log';

    public $description = 'Clear Log';

    public function handle(): int
    {
        $userSpids = UserSpid::whereNotNull('log')->update(['log' => null]);

        $this->comment('Success');
        return self::SUCCESS;
    }
}
