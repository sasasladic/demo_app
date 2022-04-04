<?php

namespace App\Services\Games;

use Illuminate\Support\Collection;

interface GameServiceInterface
{
    public function getFields(): Collection;

    public function validate(array $data): GameResponse;

    public function play(): void;

    public function getResult(): GameResponse;
}
