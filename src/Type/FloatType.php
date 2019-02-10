<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Type;

use ptlis\SerializedDataEditor\Parser\Token;

/**
 * Class representing a serialized float value.
 */
final class FloatType implements Type
{
    /** @var float */
    private $value;


    public function __construct(float $value)
    {
        $this->value = $value;
    }

    public function get(): float
    {
        return $this->value;
    }

    public function set(float $value): void
    {
        $this->value = $value;
    }

    public function containsStringCount(string $searchTerm): int
    {
        return 0;
    }

    public function __toString(): string
    {
        return Token::PREFIX_FLOAT . ':' . $this->value . ';';
    }
}