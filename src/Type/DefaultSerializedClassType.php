<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Type;

use ptlis\SerializedDataEditor\TypeFragment\ClassProperty;

final class DefaultSerializedClassType implements Type
{
    /** @var string */
    private $className;

    /** @var ClassProperty[] */
    private $propertyList;

    /**
     * @param string $className
     * @param ClassProperty[] $propertyList
     */
    public function __construct(
        string $className,
        array $propertyList
    ) {
        $this->className = $className;
        $this->propertyList = $propertyList;
    }

    public function __toString(): string
    {
        return 'O:' . strlen($this->className) . ':"' . $this->className . '":' . count($this->propertyList) . ':{' . join('', $this->propertyList) . '}';
    }
}