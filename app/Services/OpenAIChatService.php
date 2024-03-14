<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class OpenAIChatService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openai.com/v1/';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function completeChat($prompt, $system, $maxTokens = 150, $model='gpt-3.5-turbo'): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post($this->baseUrl . 'chat/completions', [
            'model' => $model, 
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $system,
                ],
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'max_tokens' => $maxTokens,
        ]);

        return $response->json();
    }

    public function getSpeech($prompt, $id, $voice='') 
    {
        if (empty($voice)) {
            $values = ['alloy', 'echo', 'fable', 'onyx', 'nova', 'shimmer'];
            $voice = $values[array_rand($values)];
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,            
        ])->post($this->baseUrl . '/audio/speech', [
            'model' => 'tts-1',
            'input' => $prompt,
            'voice' => $voice,
        ]);
        
        Storage::disk('local')->put("$id.mp3", $response->body());
    }
}