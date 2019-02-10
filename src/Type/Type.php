<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Type;

/**
 * Interface that serialized types must implement.
 */
interface Type
{
    /**
     * Returns the number of times $searchTerm appears in the type.
     */
    public function containsStringCount(string $searchTerm): int;

    public function __toString(): string;
}