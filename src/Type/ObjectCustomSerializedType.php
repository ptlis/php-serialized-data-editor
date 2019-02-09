<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Type;

use ptlis\SerializedDataEditor\Parser\Token;

/**
 * Class representing an object serialized with a custom serialization format.
 */
final class ObjectCustomSerializedType implements Type
{
    /** @var string */
    private $className;

    /** @var string */
    private $serializedData;


    public function __construct(
        string $className,
        string $serializedData
    ) {
        $this->className = $className;
        $this->serializedData = $serializedData;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function setClassName(string $className): void
    {
        $this->className = $className;
    }

    public function getSerializedData(): string
    {
        return $this->serializedData;
    }

    public function setSerializedData(string $serializedData): void
    {
        $this->serializedData = $serializedData;
    }

    public function __toString(): string
    {
        return Token::PREFIX_OBJECT_CUSTOM_NAME
            . ':'
            . strlen($this->className)
            . ':"'
            . $this->className
            . '":'
            . strlen($this->serializedData)
            . ':{'
            . $this->serializedData
            . '}';
    }
}