<?php

namespace App\Strategies;

use Illuminate\Console\Command;

use App\Interfaces\StrategyInterface;
use App\Services\OpenAIChatService;
use App\Models\DataRun;
use App\Models\History;

class OpenAIContentStrategy implements StrategyInterface
{
    protected $dataRun;
    protected $cmd;

    public function __construct(DataRun $run, Command $command) 
    {
        $this->dataRun = $run;
        $this->cmd = $command;
    }

    public function loop(): bool 
    {       
        $run = $this->dataRun;

        if ($run->done) {
            return false;
        }
        
        $prompt = date('F Y', mktime(0, 0, 0, $this->dataRun->current_month, 1, $this->dataRun->current_year));
        $r = $this->getData($prompt);
        $this->cmd->line("Ran strategy");

        if (!empty(($r["error"]))) {
            $this->cmd->error("Error: ".$r["error"]["message"]);
            return false;
        }
        
        try {
            $this->saveResult($r, $run);
        } catch (\Exception $e) {
            $this->cmd->error("Error saving result: ".$e->getMessage());
            return false;
        }
        
        $run->next();  

        return true;
    }

    public function getData(string $prompt): array
    {        
        $system = $this->dataRun->systemRole->role; 
        $maxTokens = $this->dataRun->systemRole->max_tokens;
        $model = $this->dataRun->systemRole->model;
        
        $chatService = new OpenAIChatService(env('OPENAI_API_KEY'));

        return $chatService->completeChat($prompt, $system, $maxTokens, $model);
    }

    protected function saveResult(array $result, DataRun $run): void
    {
        $this->cmd->info("Trying to save row:");        
        $this->cmd->line($result["choices"][0]["message"]["content"]);
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