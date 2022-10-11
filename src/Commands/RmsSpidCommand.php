<?php

namespace DeveloperUnijaya\RmsSpid\Commands;

use Illuminate\Console\Command;

class RmsSpidCommand extends Command
{
    public $signature = 'spid:command';

    public $description = 'spid:command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
