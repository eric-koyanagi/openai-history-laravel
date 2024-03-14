<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\History;
use App\Models\DataRun;
use App\Strategies\OpenAIContentStrategy;
use App\Strategies\OpenAIPoemStrategy;

class GetHistories extends Command
{

    const SANITY_LIMIT = 1;

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
        //$strategy = new OpenAIContentStrategy( $run, $this ); 
        $strategy = new OpenAIPoemStrategy( $run, $this ); 
        
        $i = 0;
        while ($strategy->loop()) {
            $i++;
            if ($i > self::SANITY_LIMIT) {
                break;
            }
        }
        
        $this->info("Done importing data.");
    }
}
