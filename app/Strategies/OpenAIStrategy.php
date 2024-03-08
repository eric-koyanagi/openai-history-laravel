<?php

namespace App\Strategies;

use App\Interfaces\StrategyInterface;
use App\Services\OpenAIChatService;
use App\Models\SystemRole;

class OpenAIStrategy implements StrategyInterface
{
    protected $systemRole;

    public function __construct(SystemRole $role) 
    {
        $this->systemRole = $role;
    }

    public function run(string $prompt) 
    {
        $chatService = new OpenAIChatService(env('OPENAI_API_KEY'));
    }
}