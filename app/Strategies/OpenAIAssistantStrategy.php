<?php

namespace App\Strategies;

use App\Services\OpenAIAssistantService;
use Illuminate\Console\Command;

use App\Interfaces\StrategyInterface;
use App\Services\OpenAIChatService;
use App\Models\DataRun;
use App\Models\HistoryLibrary;
use App\Models\HistoryPoem;

class OpenAIAssistantStrategy implements StrategyInterface
{
    protected $dataRun;
    protected $cmd;
    protected $assistantService;

    public function __construct(DataRun $run, Command $command) 
    {
        $this->dataRun = $run;
        $this->cmd = $command;
        $this->assistantService = new OpenAIAssistantService(env('OPENAI_API_KEY'));
    }

    public function loop(): bool 
    {       
        $run = $this->dataRun;

        if ($run->done) {
            return false;
        }
        
        // TODO this is just a proof of concept for the code
        // We can explore a lot of this use case in the playground, the code isn't that interesting

        // Upload the file, then attach the file when creating the assistant
        $fileResponse = $this->assistantService->uploadFile("/example/file.pdf");
        $this->assistantService->create("Test Assistant", "Test Prompt", ["retrieval"], $fileResponse);

        $run->next(); 
        
        // this means it only does pulls one event at a time
        return false; 
    }

    public function getData(string $prompt): array
    {        
        return [];
    }

    protected function saveResult(array $result, DataRun $run): void
    {
        
    }
}