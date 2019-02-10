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
 * Class representing a single element in an array, indexed by an integer.
 */
final class ArrayElementIntegerIndex implements ArrayElement
{
    /** @var int */
    private $index;

    /** @var Type */
    private $value;


    public function __construct(int $index, Type $value)
    {
        $this->index = $index;
        $this->value = $value;
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function setIndex(int $index): void
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

    public function replaceString(string $searchTerm, string $replaceTerm): void
    {
        $this->value->replaceString($searchTerm, $replaceTerm);
    }

    public function __toString(): string
    {
        return Token::PREFIX_INTEGER . ':' . $this->index . ';' . $this->value;
    }
}