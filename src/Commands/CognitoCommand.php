<?php

namespace Iwouldrathercode\Cognito\Commands;

use Illuminate\Console\Command;

class CognitoCommand extends Command
{
    public $signature = 'cognito';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
