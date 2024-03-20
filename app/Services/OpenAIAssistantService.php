<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class OpenAIAssistantService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openai.com/v1/';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function create(string $name, string $prompt, array $tools = [], string $model='gpt-4', array $files = []): array
    {
        $data = [
            'instructions' => $prompt,
            'model' => $model, 
            'name' => $name,
            'tools' => $tools,            
        ];

        if (!empty($files)) {
            $data['files'] = $files;
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post($this->baseUrl . 'assistants', $data);

        return $response->json();
    }

    public function uploadFile(string $filePath) 
    {
        $client = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ]);

        return $client->attach('file', file_get_contents($filePath))->post($this->baseUrl . 'files', [
                'purpose' => 'assistants',
        ]);         
    }

}