<?php

namespace App\Domain\Entity;

class UserEntity implements \JsonSerializable
{
    private int $id;
    private string $email;
    private string $fullName;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'fullName' => $this->fullName,
        ];
    }
}