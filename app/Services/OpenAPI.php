<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIChatService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openai.com/v1/';
    protected $model = 'gpt-3.5-turbo'; 

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function completeChat($prompt, $maxTokens = 150)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post($this->baseUrl . 'chat/completions', [
            'model' => $this->model, 
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Assistant',
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
}