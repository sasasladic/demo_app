<?php

namespace App\Services\Games;

class GameResponse
{
    private array $data;

    private string $message;

    private bool $success;

    private int $code;

    public function __construct(string $message = 'Success', bool $success = true, int $code = 200, array $data = [])
    {
        $this->message = $message;
        $this->success = $success;
        $this->code = $code;
        $this->data = $data;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * In case we want to return directly all data from controller.
     */
    public function toArray(): array
    {
        return [
            'data' => $this->getData(),
            'success' => $this->isSuccess(),
            'message' => $this->getMessage(),
            'code' => $this->getCode()
        ];
    }

}
