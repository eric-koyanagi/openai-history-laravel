<?php

namespace App\Interfaces;

interface StrategyInterface 
{
    public function loop(): bool;
    public function getData(string $prompt) : array;
}