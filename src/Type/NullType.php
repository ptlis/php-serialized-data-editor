<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Type;

use ptlis\SerializedDataEditor\Parser\Token;

/**
 * Class representing a serialized NULL.
 */
final class NullType implements Type
{
    public function containsStringCount(string $searchTerm): int
    {
        return 0;
    }

    public function __toString(): string
    {
        return Token::PREFIX_NULL . ';';
    }
}