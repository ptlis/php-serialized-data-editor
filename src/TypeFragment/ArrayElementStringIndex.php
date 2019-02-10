<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\TypeFragment;

use ptlis\SerializedDataEditor\Parser\Token;
use ptlis\SerializedDataEditor\Type\Type;

/**
 * Class representing a single element in an array, indexed by a string.
 */
final class ArrayElementStringIndex implements ArrayElement
{
    /** @var string */
    private $index;

    /** @var Type */
    private $value;


    public function __construct(string $index, Type $value)
    {
        $this->index = $index;
        $this->value = $value;
    }

    public function getIndex(): string
    {
        return $this->index;
    }

    public function setIndex(string $index): void
    {
        $this->index = $index;
    }

    public function getValue(): Type
    {
        return $this->value;
    }

    public function setValue(Type $value): void
    {
        $this->value = $value;
    }

    public function containsStringCount(string $searchTerm): int
    {
        return $this->value->containsStringCount($searchTerm);
    }

    public function __toString(): string
    {
        return Token::PREFIX_STRING . ':' . strlen($this->index) . ':"' . $this->index . '";' . $this->value;
    }
}