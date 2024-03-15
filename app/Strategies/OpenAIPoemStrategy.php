<?php

namespace App\Strategies;

use Illuminate\Console\Command;

use App\Interfaces\StrategyInterface;
use App\Services\OpenAIChatService;
use App\Models\DataRun;
use App\Models\HistoryLibrary;
use App\Models\HistoryPoem;

class OpenAIPoemStrategy implements StrategyInterface
{
    protected $dataRun;
    protected $cmd;
    protected $chatService;

    public function __construct(DataRun $run, Command $command) 
    {
        $this->dataRun = $run;
        $this->cmd = $command;
        $this->chatService = new OpenAIChatService(env('OPENAI_API_KEY'));
    }

    public function loop(): bool 
    {       
        $run = $this->dataRun;

        if ($run->done) {
            return false;
        }
        
        $library = HistoryLibrary::where([
            ["month", $run->current_month],
            ["year", $run->current_year]
        ])->first();

        if (!$library) {
            $run->next();
            return true;
        }

        $data = $this->getData($library->event);
        list($name, $description, $poem) = explode('###', $data["choices"][0]["message"]["content"]);

        $poemRecord = HistoryPoem::create([
            'name' => $name,
            'description' => $description,
            'poem' => $poem,
            'run_id' => $run->id,
        ]);

        $this->saveAudioData($poem, $poemRecord->id);
        $this->saveImageData($library->image_prompt, $poemRecord->id);
        
        $run->next(); 
        
        // this means it only does pulls one event at a time
        // TODO create a supervisor/queue that can rate-limit (RPM) automatically
        return false; 
    }

    public function getData(string $prompt): array
    {        
        $system = $this->dataRun->systemRole->role; 
        $maxTokens = $this->dataRun->systemRole->max_tokens;
        $model = $this->dataRun->systemRole->model;
                
        return $this->chatService->completeChat($prompt, $system, $maxTokens, $model);
    }

    public function saveAudioData(string $poem, int $id) 
    {
        $this->chatService->getSpeech($poem, $id);        
    }

    public function saveImageData(string $prompt, int $id) 
    {
        $this->chatService->getImage($prompt, $id);        
    }

    protected function saveResult(array $result, DataRun $run): void
    {
        
    }
}