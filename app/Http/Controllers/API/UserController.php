<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Resources\User\HistoryResource;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    private UserRepositoryInterface $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function history(Request $request)
    {
        if (!$request->gameId) {
            $this->returnResponseError([], 'Missing game id', 422);
        }
        $games = $this->userRepository->getGamesPlayedHistory($request->user(), $request->gameId);
        return $this->returnResponseSuccessWithPagination(HistoryResource::collection($games), 'List of scores');
    }
}
