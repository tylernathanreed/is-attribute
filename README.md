# Is Attribute

[![Automated Tests](https://github.com/tylernathanreed/is-attribute/actions/workflows/tests.yml/badge.svg)](https://github.com/tylernathanreed/is-attribute/actions/workflows/tests.yml)
[![Coding Standards](https://github.com/tylernathanreed/is-attribute/actions/workflows/coding-standards.yml/badge.svg)](https://github.com/tylernathanreed/is-attribute/actions/workflows/coding-standards.yml)
[![Code Coverage](https://github.com/tylernathanreed/is-attribute/actions/workflows/coverage.yml/badge.svg)](https://github.com/tylernathanreed/is-attribute/actions/workflows/coverage.yml)
[![Static Analysis](https://github.com/tylernathanreed/is-attribute/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/tylernathanreed/is-attribute/actions/workflows/static-analysis.yml)
[![Latest Stable Version](https://poser.pugx.org/reedware/is-attribute/v/stable)](https://packagist.org/packages/reedware/is-attribute)

This package adds a truth test helper for checking if class is an attribute.

## Introduction

[PHP Attributes](https://www.php.net/manual/en/language.attributes.overview.php) were introduced in PHP 8.0. From the documentation, you can see that Attributes are defined similar to classes:

```php
#[Attribute]
class SetUp
{
    //
}
```

However, if you're handed the name of a class, there's no good way to know if that class is a PHP Attribute or not. This is where the `is_attribute()` method, as defined by this package, comes in.

## Installation

Install this package using Composer:

```
composer require reedware/is-attribute
```

## Usage

```php
function is_attribute(string|object|null $class, ?int $target = null, int $match = TARGET_MATCH_EQUALS): bool
```

### Class Argument

Let's start with the basics. For more use-cases, you'll only need to pass one parameter to `is_attribute()`, being the class itself.

Here's an example of that:

```php
echo is_attribute(SetUp::class); // true
echo is_attribute(CopyFile::class); // false
```

### Target Argument

If you care what targets the attributes support, this is where an optional second parameter comes in.

For example, you can define an attribute like so:
```php
#[Attribute(Attribute::TARGET_CLASS_CONSTANT|Attribute::TARGET_PROPERTY)]
class Serialize
{
    //
}
```

This defines a `Serialize` attribute that can applied to a class or property.

To check if an attribute can be applied to a class or property, you can pass in targets as the second parameter to `is_attribute`:

```php
echo is_attribute(Serialize::class, Attribute::TARGET_CLASS_CONSTANT|Attribute::TARGET_PROPERTY); // true
```

Note that checking one or the other will return false:
```php
echo is_attribute(Serialize::class, Attribute::TARGET_CLASS_CONSTANT); // false
echo is_attribute(Serialize::class, Attribute::TARGET_PROPERTY); // false
```

When the second argument is not specified (e.g. `null`), the specified class must simply be an Attribute, regardless of target. This is different from passing in `Attribute::TARGET_ALL` as the second argument, which will require the attribute to specify all targets (which is the default).

```php
echo is_attribute(Serialize::class, Attribute::TARGET_ALL); // false
```

This is because the second parameter must be an *exact* match to the attribute.

### Match Argument

If you don't want to do an exact match, you can use the optional third parameter.

#### TARGET_MATCH_EQUALS

This is the default value of the third argument, and exhibits the behavior already described above.

#### TARGET_MATCH_INCLUDES

This match setting requires ALL of the provided targets to be included (e.g. and/conjunction).

```php
echo is_attribute(Serialize::class, Attribute::TARGET_CLASS_CONSTANT, TARGET_MATCH_INCLUDES); // true
echo is_attribute(Serialize::class, Attribute::TARGET_PROPERTY, TARGET_MATCH_INCLUDES); // true
echo is_attribute(Serialize::class, Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER, TARGET_MATCH_INCLUDES); // false
```

#### TARGET_MATCH_ANY

This match setting requires ANY of the provided targets to be included (e.g. or/disjunction).

```php
echo is_attribute(Serialize::class, Attribute::TARGET_CLASS_CONSTANT, TARGET_MATCH_INCLUDES); // true
echo is_attribute(Serialize::class, Attribute::TARGET_PROPERTY, TARGET_MATCH_INCLUDES); // true
echo is_attribute(Serialize::class, Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER, TARGET_MATCH_INCLUDES); // true
```
