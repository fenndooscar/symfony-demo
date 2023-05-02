<?php

declare(strict_types = 1);

namespace App\OpenApi\V1\Response;

use Attribute;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class NotFound extends OA\Response
{
    public function __construct()
    {
        parent::__construct(
            null,
            404,
            'Not found!',
            null,
            new OA\JsonContent(properties: [
                new OA\Property('meta', type: 'object', example: [
                    'success' => false,
                ]),
                new OA\Property('error', properties: [
                    new OA\Property('message', example: 'Message'),
                ], type: 'object'),
            ])
        );
    }
}
