<?php

declare(strict_types = 1);

namespace App\OpenApi\V1\Response\Created;

use Attribute;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class ManySuccess extends OA\Response
{
    public function __construct()
    {
        parent::__construct(
            null,
            201,
            'Successfully created!',
            null,
            new OA\JsonContent(properties: [
                new OA\Property('meta', type: 'object', example: [
                    'success' => true,
                ]),
                new OA\Property('data', type: 'array', items: new OA\Items(properties: [
                    new OA\Property('id', type: 'integer', example: 1),
                ])),
            ])
        );
    }
}
