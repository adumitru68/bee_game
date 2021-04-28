<?php

namespace App\Controller;

use App\Domain\Repository\BeeRepository;
use App\Domain\Repository\GameRepository;
use App\Domain\Repository\UserRepository;
use App\Framework\AbstractController;
use App\Service\GameService;
use App\Service\PayloadService;

class GameController extends AbstractController
{
    private UserRepository $userRepository;
    private GameRepository $gameRepository;
    private GameService $gameService;
    private PayloadService $payloadService;
    private BeeRepository $beeRepository;

    public function __construct(
        UserRepository $userRepository,
        GameRepository $gameRepository,
        GameService $gameService,
        BeeRepository $beeRepository,
        PayloadService $payloadService
    ) {
        $this->userRepository = $userRepository;
        $this->gameRepository = $gameRepository;
        $this->gameService = $gameService;
        $this->payloadService = $payloadService;
        $this->beeRepository = $beeRepository;
    }

    public function index(?int $userId): string
    {
        $user = $this->userRepository->getById($userId);
        if (null === $user) {
            http_response_code(404);
            return "User not found";
        }

        return $this->render(
            'games.phtml',
            [
                'user' => $user,
                'games' => $this->gameRepository->getUserGames($userId),
            ]
        );
    }

    public function selectGame($gameId) {
        $game = $this->gameRepository->getById($gameId);
        $swarm = $this->beeRepository->getSwarm($gameId);
        $gameHtml = $this->render('component/playgame.phtml', [
            'game' => $game,
            'swarm' => $swarm,
            'hitBeeId' => 0
        ]);
        $this->payloadService->pushData('gameHtml', $gameHtml);

        return $this->jsonRender($this->payloadService);
    }

    public function hit($gameId) {
        $beeId = $this->gameService->hitsGame($gameId);
        $game = $this->gameRepository->getById($gameId);
        $swarm = $this->beeRepository->getSwarm($gameId);
        $gameHtml = $this->render('component/playgame.phtml', [
            'game' => $game,
            'swarm' => $swarm,
            'hitBeeId' => $beeId
        ]);
        $this->payloadService->pushData('gameHtml', $gameHtml);

        return $this->jsonRender($this->payloadService);
    }

    public function createGame(int $userId)
    {
        $this->gameService->createGame($userId);
        return $this->jsonRender($this->payloadService);
    }
}