<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Carbon;

interface UserRepositoryInterface
{
    public function playedInTimeScope(User $user, int $gameId, Carbon $start, Carbon $end);

    public function getGamesPlayedHistory(User $user, int $gameId);

    public function trackPlayedGame(User $user, int $gameId, array $attributes);
}
