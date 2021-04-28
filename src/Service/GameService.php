<?php

namespace App\Service;

use App\Config\SwarmConfig;
use App\Domain\Repository\BeeRepository;
use App\Domain\Repository\GameRepository;
use App\Domain\Repository\UserRepository;

class GameService
{
    private GameRepository $gameRepository;
    private UserRepository $userRepository;
    private BeeRepository $beeRepository;
    private SwarmConfig $swarmConfig;
    private PayloadService $payloadService;

    public function __construct(
        GameRepository $gameRepository,
        UserRepository $userRepository,
        BeeRepository $beeRepository,
        SwarmConfig $swarmConfig,
        PayloadService $payloadService
    ) {
        $this->gameRepository = $gameRepository;
        $this->userRepository = $userRepository;
        $this->swarmConfig = $swarmConfig;
        $this->beeRepository = $beeRepository;
        $this->payloadService = $payloadService;
    }

    public function createGame($userId): int
    {
        try {
            $this->gameRepository->getPdo()->beginTransaction();
            $gameId = $this->gameRepository->insert($userId);
            foreach ($this->swarmConfig->getConfig() as $beeType => $beeConfig) {
                for ($i = 0; $i < $beeConfig['count']; $i++) {
                    $this->beeRepository->insert(
                        $gameId,
                        $beeType,
                        $beeConfig['healthyPoints'],
                        $beeConfig['damage']
                    );
                }
            }
            $this->gameRepository->getPdo()->commit();
        } catch (\Exception $exception) {
            if ($this->gameRepository->getPdo()->inTransaction()) {
                $this->gameRepository->getPdo()->rollBack();
            }

            throw new \Exception('Query error');
        }

        return $gameId;
    }

    public function hitsGame(int $gameId):int
    {
        $game = $this->gameRepository->getById($gameId);
        $gameEnded = $game->getEnded();
        $this->payloadService->pushData('ended', $gameEnded);
        if ($gameEnded) {
            return 0;
        }
        $swarm = $this->beeRepository->getSwarm($gameId);
        $notDeathBee = [];

        foreach ($swarm as $beeEntity) {
            if ($beeEntity->getHealthyRemain()) {
                $notDeathBee[] = $beeEntity;
            }
        }

        $beeTarget = $notDeathBee[array_rand($notDeathBee)];

        try {
            $this->gameRepository->getPdo()->beginTransaction();
            $this->gameRepository->hits($gameId);
            $this->beeRepository->hits($beeTarget->getId());
            $this->endGameIfNeeded($gameId);
            $this->gameRepository->getPdo()->commit();
            return $beeTarget->getId();
        } catch (\Exception $exception) {
            if ($this->gameRepository->getPdo()->inTransaction()) {
                $this->gameRepository->getPdo()->rollBack();
            }

            throw new \Exception('Query error');
        }
    }

    private function endGameIfNeeded(int $gameId)
    {
        $swarm = $this->beeRepository->getSwarm($gameId);
        $lifeBee = [];

        foreach ($swarm as $beeEntity) {
            if($beeEntity->getBeeType() === SwarmConfig::BEE_QUEEN and $beeEntity->getHealthyRemain() <=0) {
                $this->gameRepository->endGame($gameId);
                return;
            }
            if ($beeEntity->getHealthyRemain()) {
                $lifeBee[] = $beeEntity;
            }
        }

        if(!$lifeBee) {
            $this->gameRepository->endGame($gameId);
        }
    }
}