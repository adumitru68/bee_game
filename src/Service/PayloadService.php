<?php

namespace App\Service;

class PayloadService implements \JsonSerializable
{
    private array $data = [];
    private $statusCode = 200;
    private $message = 'Success';
    private bool $isError = false;

    public function pushData(string $key, $value): PayloadService
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function pushError(int $errorCode, $message): PayloadService
    {
        if ($this->isError) {
            return $this;
        } else {
            $this->statusCode = $errorCode;
            $this->message = $message;
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'message' => $this->message,
            'data' => $this->isError ? [] : $this->data
        ];
    }
}