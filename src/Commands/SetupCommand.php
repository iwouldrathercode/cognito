<?php

namespace Iwouldrathercode\Cognito\Commands;

use Exception;
use Illuminate\Console\Command;

class SetupCommand extends Command
{
    public $signature = 'cognito:setup';

    public $description = 'Modify project files to allow api authentication only using amazon cognito';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        
        $this->installDoctrineDBAL();

        $this->linkStorageToPublicFolder();

        return self::SUCCESS;
    }

    protected function installDoctrineDBAL()
    {
        try {
            exec("composer require doctrine/dbal");
        } catch (Exception $exception) {
            $this->comment("doctrine/dbal package is used to make DB table changes. Ensure you are not ");
        }
    }

    protected function linkStorageToPublicFolder()
    {
        try {
            $this->call("storage:link");
        } catch (Exception $exception) {
            $this->comment("Storage is already linked!, nothing to do.");
        }
    }
}
