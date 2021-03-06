<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Type;

use ptlis\SerializedDataEditor\Parser\Token;

/**
 * Class representing a serialized integer value.
 */
final class IntegerType implements Type
{
    /** @var int */
    private $value;


    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function get(): int
    {
        return $this->value;
    }

    public function set(int $value): void
    {
        $this->value = $value;
    }

    public function containsStringCount(string $searchTerm): int
    {
        return 0;
    }

    public function replaceString(string $searchTerm, string $replaceTerm): void
    {
        // Do nothing
    }

    public function __toString(): string
    {
        return Token::PREFIX_INTEGER . ':' . $this->value . ';';
    }
}