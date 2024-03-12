<?php

namespace App\Interfaces;

interface StrategyInterface 
{
    public function run(string $prompt) : array;
}