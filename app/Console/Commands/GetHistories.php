<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\SystemRole;
use App\Models\DataRun;
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
        // 1. Load or Create a DataRun, which tracks the progress of each full pull from APIs, then instantiate my strategy
        $run = DataRun::getActiveRun( $this->argument('role'));
        $strategy = new OpenAIStrategy( $run ); // TODO use a factory here if flexibility with many APIs needed

        // 2. Iterate the run for each month and year, executing the strategy to pull data      
        $result = [];
        while ( !$run->done ) 
        {
            $r = $strategy->run($run);
            $this->line($r);
            $run->next();

            $result[] = $r;
        }

        var_dump($result);

        // 3. Save to the database (or maybe do this with each line, or wrap this in a finally block)
        // TODO
    }

}
