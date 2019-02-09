<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Type;

use ptlis\SerializedDataEditor\TypeFragment\ArrayElement;

/**
 * Class representing a serialized array value.
 */
final class ArrayType implements Type
{
    private $elementList;

    /**
     * @param ArrayElement[] $elementList
     */
    public function __construct(array $elementList)
    {
        $this->elementList = $elementList;
    }
    // TODO: Implement

    public function __toString(): string
    {
        return 'a:' . count($this->elementList) . ':{' . join('', $this->elementList) . '}';
    }
}