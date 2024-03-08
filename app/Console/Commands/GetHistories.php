<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\SystemRole;
use App\Models\DataRun
use App\Strategies\OpenAIStrategy;

class GetHistories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-histories {role : The ID of the SystemRole to use}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Get a strategy, populating it with the system role from input arguments
        $strategy = $this->getStrategy( $this->argument('role') );

        // 2. Load or Create a DataRun, which tracks the progress of each full pull from APIs
        $run = DataRun::getActiveRun();

        // 3. Query and save data for each month in each year until there's nothing left        
        while ( !$run->done ) 
        {
            $result = $strategy->run($run);
            $this->line($result);
            $run->next();
        }
    }

    protected function getStrategy($roleId) 
    {        
        $systemRole = SystemRole::find($roleId);
        if (!$systemRole) {
            $this->error('No role found');
        }
        
        // TODO use a factory if I want to return a different strategy, e.g. to experiment with different API types
        return new OpenAIStrategy($systemRole);
    }
}
