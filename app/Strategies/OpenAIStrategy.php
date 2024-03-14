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

    public function run(string $prompt): array
    {
        $prompt = date('F Y', mktime(0, 0, 0, $this->dataRun->current_month, 1, $this->dataRun->current_year)); //$this->dataRun->currentMonth . ' ' . $this->dataRun->currentYear;
        $system = $this->dataRun->systemRole->role; 
        $maxTokens = $this->dataRun->systemRole->max_tokens;
        $model = $this->dataRun->systemRole->model;
        
        $chatService = new OpenAIChatService(env('OPENAI_API_KEY'));

        return $chatService->completeChat($prompt, $system, $maxTokens, $model);
    }
}