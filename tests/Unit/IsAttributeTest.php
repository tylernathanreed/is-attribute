<?php

use Reedware\IsAttribute\Tests\Fixtures\AllAttribute;
use Reedware\IsAttribute\Tests\Fixtures\CallableAttribute;
use Reedware\IsAttribute\Tests\Fixtures\GenericAttribute;
use Reedware\IsAttribute\Tests\Fixtures\PropertyAttribute;

$tests = [
    'returns false for null' => [null, false],
    'returns false for scalars' => ['scalar', false],
    'returns false for non attribute class name' => [UnitEnum::class, false],
    'returns false for non attribute class instance' => [new stdclass, false],
    'returns true for attribute class name' => [GenericAttribute::class, true],
    'returns true for attribute class instance' => [new GenericAttribute, true],
    'returns true for generic attribute with target' => [GenericAttribute::class, Attribute::TARGET_FUNCTION, true],
    'returns false for all attribute with exact target' => [AllAttribute::class, Attribute::TARGET_FUNCTION, false],
    'returns true for all attribute with all exact target' => [AllAttribute::class, Attribute::TARGET_ALL, true],
    'returns true for all attribute with inclusive target' => [AllAttribute::class, Attribute::TARGET_FUNCTION, TARGET_MATCH_INCLUDES, true],
    'returns false for specific attribute with wrong exact target' => [PropertyAttribute::class, Attribute::TARGET_FUNCTION, false],
    'returns true for specific attribute with correct exact target' => [PropertyAttribute::class, Attribute::TARGET_PROPERTY, true],
    'returns false for multi attribute with one exact target' => [CallableAttribute::class, Attribute::TARGET_FUNCTION, false],
    'returns true for multi attribute with one inclusive target' => [CallableAttribute::class, Attribute::TARGET_FUNCTION, TARGET_MATCH_INCLUDES, true],
    'returns true for multi attribute with multi exact target' => [CallableAttribute::class, Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD, true],
    'returns false for specific attribute with multi exact target' => [PropertyAttribute::class, Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER, false],
    'returns false for specific attribute with multi inclusive target' => [PropertyAttribute::class, Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER, TARGET_MATCH_INCLUDES, false],
    'returns true for specific attribute with multi any target' => [PropertyAttribute::class, Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER, TARGET_MATCH_ANY, true],
];

foreach ($tests as $name => $parameters) {
    it($name, function () use ($parameters) {
        $expected = array_pop($parameters);
        expect(is_attribute(...$parameters))->toBe($expected);
    });
}

it('defines the target match constants', function (string $name, int $value) {
    expect(defined($name))->toBeTrue();
    expect(constant($name))->toEqual($value);
})->with([
    ['TARGET_MATCH_EQUALS', 1],
    ['TARGET_MATCH_INCLUDES', 2]
]);

it('defines the target match constants as powers of 2', function (string $name) {
    $value = constant($name);
    expect($value)->toBeInt();

    $isPowerOfTwo = ($value & ($value - 1)) === 0;
    expect($isPowerOfTwo)->toBeTrue();
})->with([
    'TARGET_MATCH_EQUALS',
    'TARGET_MATCH_INCLUDES'
]);