<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

trait JsonRespond
{
    protected int $httpStatusCode = 200;
    
    protected ?int $errorCode = null;

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function setHttpStatusCode(int $httpStatusCode): self
    {
        $this->httpStatusCode = $httpStatusCode;

        return $this;
    }

    public function getErrorCode(): ?int
    {
        return $this->errorCode;
    }

    public function setErrorCode(int $errorCode): self
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    public function respond(array $data, array $headers = []): JsonResponse
    {
        return response()->json($data, $this->getHttpStatusCode(), $headers);
    }

    public function respondNotFound(): JsonResponse
    {
        return $this->setHttpStatusCode(404)
            ->setErrorCode(31)
            ->respondWithError();
    }

    public function respondValidatorFailed(Validator $validator): JsonResponse
    {
        return $this->setHttpStatusCode(422)
            ->setErrorCode(32)
            ->respondWithError($validator->errors()->all());
    }

    public function respondNotTheRightParameters(?string $message = null): JsonResponse
    {
        return $this->setHttpStatusCode(500)
            ->setErrorCode(33)
            ->respondWithError($message);
    }

    public function respondInvalidQuery(?string $message = null): JsonResponse
    {
        return $this->setHttpStatusCode(500)
            ->setErrorCode(40)
            ->respondWithError($message);
    }

    public function respondInvalidParameters(?string $message = null): JsonResponse
    {
        return $this->setHttpStatusCode(422)
            ->setErrorCode(41)
            ->respondWithError($message);
    }

    public function respondUnauthorized(?string $message = null): JsonResponse
    {
        return $this->setHttpStatusCode(401)
            ->setErrorCode(42)
            ->respondWithError($message);
    }

    public function respondWithError(array|string|null $message = null): JsonResponse
    {
        return $this->respond([
            'error' => [
                'message' => $message ?? config('api.error_codes.'.$this->getErrorCode()),
                'error_code' => $this->getErrorCode(),
            ],
        ]);
    }

    public function respondObjectDeleted(string $id): JsonResponse
    {
        return $this->respond([
            'deleted' => true,
            'id' => $id
        ]);
    }
}
