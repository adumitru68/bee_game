<?php

namespace App\Config;

class SwarmConfig
{
    public const BEE_QUEEN = 1;
    public const BEE_WORKER = 2;
    public const BEE_DRONE = 3;

    private array $config;

    public function __construct(array $swarmConfig)
    {
        $this->config = $swarmConfig;
    }

    public function getName(int $beeType): string
    {
        $activeBeeType = [
            self::BEE_QUEEN => 'Queen',
            self::BEE_WORKER => 'Worker',
            self::BEE_DRONE => 'Drone',
        ];

        if (!isset($activeBeeType[$beeType])) {
            throw new ConfigException('Invalid bee type');
        }

        return $activeBeeType[$beeType];
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}