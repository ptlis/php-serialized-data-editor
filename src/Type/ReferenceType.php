<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Type;

use ptlis\SerializedDataEditor\Parser\Token;

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

    public function containsStringCount(string $searchTerm): int
    {
        // TODO: Properly handle this - should it return the value for the referred-to component?
        return 0;
    }

    public function replaceString(string $searchTerm, string $replaceTerm): void
    {
        // TODO: Properly handle this - should it return the value for the referred-to component?
    }

    public function __toString(): string
    {
        return Token::PREFIX_REFERENCE . ':' . $this->referenceIndex . ';';
    }
}