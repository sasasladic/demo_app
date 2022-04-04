<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Game\GamePlayRequest;
use App\Http\Resources\FormField\Resources\FormFieldResource;
use App\Http\Resources\Game\GameResource;
use App\Http\Resources\User\HistoryResource;
use App\Models\Game;
use App\Models\User;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\Games\GameServiceInterface;
use Illuminate\Http\JsonResponse;

class GameController extends BaseController
{

    private UserRepositoryInterface $userRepository;

    private BaseRepositoryInterface $baseRepository;

    private GameServiceInterface $gameService;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param BaseRepositoryInterface $baseRepository
     * @param GameServiceInterface $gameService
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        BaseRepositoryInterface $baseRepository,
        GameServiceInterface $gameService
    )
    {
        $this->userRepository = $userRepository;
        $this->baseRepository = $baseRepository;
        $this->gameService = $gameService;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->returnResponseSuccessWithPagination(
            GameResource::collection($this->baseRepository->index(Game::class)),
            'List of games'
        );
    }

    /**
     * Required fields for the game.
     *
     * @return JsonResponse
     */
    public function getFields(): JsonResponse
    {
        return $this->returnResponseSuccess(FormFieldResource::collection($this->gameService->getFields()), 'List of fields');
    }

    /**
     * 1. Check if user has played certain game today or in any time scope.
     * 2. Validate and set game data
     * 3. Play game
     * 4. Get result
     *
     * @param GamePlayRequest $request
     * @return JsonResponse
     */
    public function play(GamePlayRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $gameId = $request->game_id;

        $playedToday = $this->userRepository->playedInTimeScope($user, $gameId, now()->startOfDay(), now()->endOfDay());
        if ($playedToday) {
            return $this->returnResponseError([], 'Already played today!', 403);
        }

        $validation = $this->gameService->validate($request->input);
        if (!$validation->isSuccess()) {
//            return $this->returnResponseError($validation->getData(), $validation->getMessage(), $validation->getCode());
            return response()->json($validation->toArray(), $validation->getCode());
        }

        $this->gameService->play();
        $result = $this->gameService->getResult();

        $this->userRepository->trackPlayedGame($user, $gameId, $result->getData());

        if ($result->isSuccess()) {
            return $this->returnResponseSuccess(new HistoryResource($user->games()->first()), $result->getMessage(), code: $result->getCode());
        }

        return $this->returnResponseError([], $result->getMessage(), code: $result->getCode());
    }
}
