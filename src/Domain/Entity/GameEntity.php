<?php

namespace App\Domain\Entity;

class GameEntity implements \JsonSerializable
{
    private int $id;
    private int $userId;
    private int $ended;
    private int $hits;
    private string $userName;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getEnded(): int
    {
        return $this->ended;
    }

    public function getHits(): int
    {
        return $this->hits;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'ended' => $this->ended,
            'hits' => $this->hits,
            'userName' => $this->userName
        ];
    }
}