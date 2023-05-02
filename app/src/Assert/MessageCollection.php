<?php

declare(strict_types = 1);

namespace App\Assert;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class MessageCollection extends Constraint
{
}
