<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\TypeFragment;

use ptlis\SerializedDataEditor\Tokenizer\Token;
use ptlis\SerializedDataEditor\Type\Type;

/**
 * Class representing a single property of an object.
 */
final class ObjectProperty
{
    const PUBLIC = 'public';
    const PROTECTED = 'protected';
    const PRIVATE = 'private';

    /** @var string */
    private $visibility;

    /** @var string */
    private $className;

    /** @var string */
    private $propertyName;

    /** @var Type */
    private $value;


    public function __construct(
        string $visibility,
        string $className,
        string $propertyName,
        Type $value
    ) {
        $this->visibility = $visibility;
        $this->className = $className;
        $this->propertyName = $propertyName;
        $this->value = $value;
    }

    public function getVisibility(): string
    {
        return $this->visibility;
    }

    public function setVisibility(string $visibility): void
    {
        $this->visibility = $visibility;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function setClassName(string $className): void
    {
        $this->className = $className;
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    public function setPropertyName(string $propertyName): void
    {
        $this->propertyName = $propertyName;
    }

    public function getValue(): Type
    {
        return $this->value;
    }

    public function setValue(Type $value): void
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        $serializedPropName = '';
        switch ($this->visibility) {
            case self::PROTECTED:
                $serializedPropName = "\0*\0";
                break;

            case self::PRIVATE:
                $serializedPropName = "\0" . $this->className . "\0";
                break;
        }
        $serializedPropName .= $this->propertyName;

        return Token::PREFIX_STRING . ':' . strlen($serializedPropName) . ':"' . $serializedPropName . '";' . strval($this->value);
    }
}