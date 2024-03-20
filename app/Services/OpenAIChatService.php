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

    public function completeChat(string $prompt, string $system, int $maxTokens = 150, string $model='gpt-3.5-turbo'): array
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

    public function getSpeech(string $prompt, int $id, string $voice='') 
    {
        if (empty($voice)) {
            $values = ['alloy', 'echo', 'fable', 'onyx', 'nova', 'shimmer'];
            $voice = $values[array_rand($values)];
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,            
        ])->post($this->baseUrl . 'audio/speech', [
            'model' => 'tts-1',
            'input' => $prompt,
            'voice' => $voice,
        ]);
        
        Storage::disk('local')->put("$id.mp3", $response->body());
    }

    public function getImage(string $prompt, int $id, string $model='dall-e-3') 
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,            
        ])->post($this->baseUrl . 'images/generations', [
            'model' => $model,
            'prompt' => $prompt,
            'n' => 1,
            'size' => '1024x1024',
            'response_format' => 'b64_json'
        ]);

        if ($response->successful()) 
        {            
            $responseData = $response->json();
            $imageData = $responseData['data'][0]['b64_json'];
            Storage::disk('local')->put("$id.png", base64_decode($imageData));
        } else {
            echo "UNABLE TO MAKE IMAGE";
            var_dump([
                'model' => $model,
                'prompt' => $prompt,
                'n' => 1,
                'size' => '1024x1024',
                'response_format' => 'b64_json'
            ]);
            var_dump($response->status());
        }               
    }
}