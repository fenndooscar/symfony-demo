<?php

declare(strict_types = 1);

namespace App\OpenApi\V1\Response;

use Attribute;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class BadRequest extends OA\Response
{
    public function __construct()
    {
        parent::__construct(
            null,
            400,
            'Bad Request',
            null,
            new OA\JsonContent(properties: [
                new OA\Property('meta', type: 'object', example: [
                    'success' => false,
                ]),
                new OA\Property('error', properties: [
                    new OA\Property('message', example: 'Bad request message'),
                    new OA\Property('details', type: 'object', nullable: true),
                ], type: 'object'),
            ])
        );
    }
}
