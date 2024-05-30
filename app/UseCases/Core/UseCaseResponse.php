<?php

namespace App\UseCases\Core;

class UseCaseResponse
{
    public function __construct(
        protected bool $success,
        protected ?string $message,
        protected int $code,
        protected mixed $data = null
    ) {}

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'code' => $this->code,
            'message' => $this->message,
            'data' => $this->data
        ];
    }
}
