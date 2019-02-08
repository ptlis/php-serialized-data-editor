<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Type;

use ptlis\SerializedDataEditor\Tokenizer\Token;

/**
 * Class representing a serialized reference.
 *
 * TODO: How on earth can this be safely mutated?
 */
final class ReferenceType implements Type
{
    /** @var int */
    private $referenceIndex;


    public function __construct(int $referenceIndex)
    {
        $this->referenceIndex = $referenceIndex;
    }

    public function get(): int
    {
        return $this->referenceIndex;
    }

    public function set(int $referenceIndex): void
    {
        $this->referenceIndex = $referenceIndex;
    }

    public function __toString(): string
    {
        return Token::PREFIX_REFERENCE . ':' . $this->value . ';';
    }
}