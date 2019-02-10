<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Type;

use ptlis\SerializedDataEditor\TypeFragment\ObjectProperty;

/**
 * Class representing an object serialized with PHP's default object serializer.
 */
final class ObjectDefaultSerializedType implements Type
{
    /** @var string */
    private $className;

    /** @var ObjectProperty[] */
    private $propertyList;

    /**
     * @param string $className
     * @param ObjectProperty[] $propertyList
     */
    public function __construct(
        string $className,
        array $propertyList
    ) {
        $this->className = $className;
        $this->propertyList = $propertyList;
    }

    public function containsStringCount(string $searchTerm): int
    {
        $count = 0;
        foreach ($this->propertyList as $property) {
            $count += $property->containsStringCount($searchTerm);
        }
        return $count;
    }

    public function __toString(): string
    {
        return 'O:' . strlen($this->className) . ':"' . $this->className . '":' . count($this->propertyList) . ':{' . join('', $this->propertyList) . '}';
    }
}