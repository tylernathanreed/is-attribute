<?php

// @codeCoverageIgnoreStart
if (! function_exists('is_attribute')) {
    // @codeCoverageIgnoreEnd
    define('TARGET_MATCH_EQUALS', 1);
    define('TARGET_MATCH_INCLUDES', 2);
    define('TARGET_MATCH_ANY', 4);

    /**
     * Returns whether or not the specified class is an attribute.
     */
    function is_attribute(string|object|null $class, ?int $target = null, int $match = TARGET_MATCH_EQUALS): bool
    {
        if (is_null($class)) {
            return false;
        }

        if (is_object($class)) {
            $class = get_class($class);
        }

        if (! class_exists($class)) {
            return false;
        }

        $reflection = new ReflectionClass($class);

        $attributes = $reflection->getAttributes();

        foreach ($attributes as $attribute) {
            if ($attribute->getName() != Attribute::class) {
                continue;
            }

            if (is_null($target)) {
                return true;
            }

            if (empty($arguments = $attribute->getArguments())) {
                return true;
            }

            $argument = $arguments[0];

            return match ($match) {
                // Must be an exact match
                TARGET_MATCH_EQUALS => $target === $argument,

                // Must include all of the specified targets
                TARGET_MATCH_INCLUDES => ($target & $argument) === $target,

                // Must include at least one of the specified targets
                TARGET_MATCH_ANY => ($target & $argument) > 0,

                // Unknown match operator
                default => throw new InvalidArgumentException(sprintf(
                    'Undefined target match constant [%s].',
                    $match
                ))
            };
        }

        return false;
    }
}
