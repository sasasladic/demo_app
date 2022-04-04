<?php

namespace App\Repositories\Implementation;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    /**
     * Check if user has played a game in provided time scope.
     *
     * @param User $user
     * @param int $gameId
     * @param Carbon $start
     * @param Carbon $end
     * @return bool
     */
    public function playedInTimeScope(User $user, int $gameId, Carbon $start, Carbon $end): bool
    {
        return $user->games()
            ->where('game_id', $gameId)
            ->wherePivotBetween('created_at', [$start, $end])
            ->exists();
    }

    /**
     * @param User $user
     * @param int $gameId
     * @return LengthAwarePaginator
     */
    public function getGamesPlayedHistory(User $user, int $gameId): LengthAwarePaginator
    {
        return $user->games()->where('game_id', $gameId)->paginate(15);
    }

    /**
     * @param User $user
     * @param int $gameId
     * @param array $attributes
     * @return void
     */
    public function trackPlayedGame(User $user, int $gameId, array $attributes)
    {
        $user->games()->attach($gameId, $attributes);
    }
}
