<?php

declare(strict_types = 1);

namespace App\Http\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use function array_map;

class JsonResponder
{
    /**
     * @param array<string,mixed> $object
     */
    public function okOne(array $object): JsonResponse
    {
        return $this->getResponse([
            'meta' => ['success' => true],
            'data' => $object,
        ], Response::HTTP_OK);
    }

    /**
     * @param array<array<string,mixed>> $list
     */
    public function okMany(array $list, int $total, int $limit, int $offset): JsonResponse
    {
        return $this->getResponse([
            'meta' => [
                'success' => true,
                'total'   => $total,
                'limit'   => $limit,
                'offset'  => $offset,
            ],
            'data' => $list,
        ], Response::HTTP_OK);
    }

    /**
     * @param int[] $ids
     */
    public function createdMany(array $ids): JsonResponse
    {
        $data = array_map(static function ($id) {
            return ['id' => $id];
        }, $ids);

        return $this->getResponse([
            'meta' => ['success' => true],
            'data' => $data,
        ], Response::HTTP_CREATED);
    }

    public function noContent(): JsonResponse
    {
        return $this->getResponse([
            'meta' => ['success' => true],
            'data' => [],
        ], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int[] $ids
     */
    public function updatedMany(array $ids): JsonResponse
    {
        $data = array_map(static function ($id) {
            return ['id' => $id];
        }, $ids);

        return $this->getResponse([
            'meta' => ['success' => true],
            'data' => $data,
        ], Response::HTTP_OK);
    }

    public function accepted(int|string $id): JsonResponse
    {
        return $this->getResponse([
            'meta' => ['success' => true],
            'data' => ['id' => $id],
        ], Response::HTTP_ACCEPTED);
    }

    public function badRequest(string $message): JsonResponse
    {
        return $this->getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => $message],
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param array<int|string, mixed> $errors
     */
    public function validationFailed(array $errors): JsonResponse
    {
        return $this->getResponse([
            'meta'  => ['success' => false],
            'error' => [
                'message' => 'Validation failed',
                'details' => $errors,
            ],
        ], Response::HTTP_BAD_REQUEST);
    }

    public function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => $message],
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => $message],
        ], Response::HTTP_FORBIDDEN);
    }

    public function notFound(string $message = 'Not found'): JsonResponse
    {
        return $this->getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => $message],
        ], Response::HTTP_NOT_FOUND);
    }

    public function methodNotAllowed(): JsonResponse
    {
        return $this->getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => 'Method not allowed'],
        ], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function internalServerError(): JsonResponse
    {
        return $this->getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => 'Internal server error'],
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function badGateway(): JsonResponse
    {
        return $this->getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => 'Bad gateway'],
        ], Response::HTTP_BAD_GATEWAY);
    }

    public function serviceUnavailable(): JsonResponse
    {
        return $this->getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => 'Service unavailable'],
        ], Response::HTTP_SERVICE_UNAVAILABLE);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function getResponse(array $data, int $status): JsonResponse
    {
        return new JsonResponse($data, $status);
    }
}
