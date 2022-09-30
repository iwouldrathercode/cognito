<?php

namespace Iwouldrathercode\Cognito\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class SetupCommand extends Command
{
    public $signature = 'cognito:setup';

    public $description = 'Modify project files to allow api authentication only using amazon cognito';

    public function handle(): int
    {

        Config::set('auth.defaults.guard', 'api');


        // $choicePrompt = 'This command will modify project files, which approach would you want to take?';
        // $choiceOptions = [
        //     'Prompt for confirmation while modifying each file',
        //     'Allow package to modify project files without further prompts'
        // ];
        // $choiceDefault = 0;
        // $allowMultipleSelections = false;
        // $choice = $this->choice($choicePrompt, $choiceOptions, $choiceDefault, $allowMultipleSelections);

        // if($choice) {

        //     if ($this->confirm('Modify routes/web.php by updating the "/" - landing page route ?', true)) {
        //         $this->modifyLandingWebRoute();
        //     }

        //     if ($this->confirm('Modify routes/web.php by adding unauthenticated route ?', true)) {
        //         $this->addUnauthenticatedWebRoute();
        //     }

        //     if ($this->confirm('Modify routes/web.php by redirecting all web routes to 404 (to allow API only routes) ?', true)) {
        //         $this->redirectNonAPIRoutes();
        //     }

        // } else {

        //     $this->modifyLandingWebRoute();
        //     $this->addUnauthenticatedWebRoute();
        //     $this->redirectNonAPIRoutes();

        // }

        return self::SUCCESS;
    }

    protected function modifyLandingWebRoute()
    {

    }

    protected function addUnauthenticatedWebRoute()
    {

    }

    protected function redirectNonAPIRoutes()
    {

    }
}
