<?php

namespace Reedware\IsAttribute\Tests\Fixtures;

use Attribute;

#[Attribute(Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD)]
class CallableAttribute
{

}
