<?php

namespace App\UseCases\Core;

use Symfony\Component\HttpFoundation\Response;

abstract class UseCase implements UseCaseInterface
{
    public int $success_status_code = Response::HTTP_OK;
    public string $response_message = 'Action success';

    public function handle(): UseCaseResponse
    {
        try {
            $response = $this->action();
            return $this->success(__($this->response_message), $response, $this->success_status_code);
        } catch( \Throwable $exception ) {
            $code = $exception->status ?? $exception->getCode();
            $code = array_key_exists($code, Response::$statusTexts) ? $code : Response::HTTP_INTERNAL_SERVER_ERROR;
            $this->fail(__($exception->getMessage()), $exception->getTrace(), $code);
        }
    }

    protected function success (
        ?string $message = null,
        mixed $data = null,
        int $code = Response::HTTP_OK
    ): UseCaseResponse
    {
        return new UseCaseResponse(
            true,
            $message,
            $code,
            $data
        );
    }

    protected function fail (
        ?string $message = null,
        mixed $data = null,
        int $code = Response::HTTP_BAD_REQUEST
    ): UseCaseResponse
    {
        return new UseCaseResponse(
            true,
            $message,
            $code,
            $data
        );
    }
}
