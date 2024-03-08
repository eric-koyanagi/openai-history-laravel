<?php

namespace App\Strategies;

use App\Interfaces\StrategyInterface;
use App\Services\OpenAIChatService;
use App\Models\DataRun;

class OpenAIStrategy implements StrategyInterface
{
    protected $dataRun;

    public function __construct(DataRun $run) 
    {
        $this->dataRun = $run;
    }

    public function run(string $prompt) 
    {
        $prompt = $this->dataRun->currentMonth . '/' . $this->dataRun->currentYear;
        $system = $this->dataRun->systemRole->role;        
        $chatService = new OpenAIChatService(env('OPENAI_API_KEY'));

        return $chatService->completeChat($prompt, $system);
    }
}