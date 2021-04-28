<?php

namespace App\Domain\Entity;

use App\Config\SwarmConfig;
use App\Framework\Container;

class BeeEntity implements \JsonSerializable
{
    private int $id;
    private int $gameId;
    private int $beeType;
    private int $healthyPoints;
    private int $damageRate;
    private int $healthyRemain;

    public function getId(): int
    {
        return $this->id;
    }

    public function getGameId(): int
    {
        return $this->gameId;
    }

    public function getBeeType(): int
    {
        return $this->beeType;
    }

    public function getBeeName(): string
    {
        /** @var SwarmConfig $swarmConfig */
        $swarmConfig = Container::getInstance()->get(SwarmConfig::class);

        return $swarmConfig->getName($this->beeType);
    }

    public function getHealthyPoints(): int
    {
        return $this->healthyPoints;
    }

    public function getDamageRate(): int
    {
        return $this->damageRate;
    }

    public function getHealthyRemain(): int
    {
        return $this->healthyRemain;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'beeName' => $this->getBeeName(),
            'healthyPoints' => $this->healthyPoints,
            'healthyRemain' => $this->healthyRemain
        ];
    }
}