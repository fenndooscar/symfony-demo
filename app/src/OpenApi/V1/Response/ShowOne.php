<?php

declare(strict_types = 1);

namespace App\OpenApi\V1\Response;

use Attribute;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

/**
 * @template T of object
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class ShowOne extends OA\Response
{
    /**
     * @param class-string<T> $dataRef
     */
    public function __construct(string $dataRef)
    {
        parent::__construct(
            null,
            200,
            'Success one!',
            null,
            new OA\JsonContent(properties: [
                new OA\Property('meta', type: 'object', example: [
                    'success' => true,
                    'limit'   => 10,
                    'offset'  => 0,
                ]),
                new OA\Property('data', ref: new Model(type: $dataRef), type: 'object'),
            ]),
        );
    }
}
