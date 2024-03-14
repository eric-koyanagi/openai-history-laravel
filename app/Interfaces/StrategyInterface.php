<?php

namespace App\Interfaces;

interface StrategyInterface 
{
    public function loop(): bool;
    public function run(string $prompt) : array;
}