<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\History;
use App\Models\DataRun;
use App\Strategies\OpenAIStrategy;

class GetHistories extends Command
{

    const SANITY_LIMIT = 24;

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
        $i = 0;
        while ( !$run->done ) 
        {
            $r = $strategy->run($run);
            $this->line("Ran strategy $i");

            if (!empty(($r["error"]))) {
                $this->error("Error: ".$r["error"]["message"]);
                break;
            }
            
            try {
                $this->saveResult($r, $run);
            } catch (\Exception $e) {
                $this->error("Error saving result: ".$e->getMessage());
                break;
            }
            
            $run->next();
            $i++;

            if ($i > self::SANITY_LIMIT) {
                break;
            }
        }

        $this->info("Done importing data.");
    }

    protected function saveResult(array $result, DataRun $run): void
    {
        $this->info("Trying to save row:");        
        $this->line($result["choices"][0]["message"]["content"]);
        $content = json_decode($result["choices"][0]["message"]["content"]);       
        $events = $content->events;

        History::create([
            'run_id' => $run->id,
            'month' => $run->current_month,
            'year' => $run->current_year,
            'event_1' => $events[0]->description ?? null,
            'event_2' => $events[1]->description ?? null,
            'event_3' => $events[2]->description ?? null,
        ]);
    }

}
