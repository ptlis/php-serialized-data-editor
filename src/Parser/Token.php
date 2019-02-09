<?php declare(strict_types=1);

/**
 * @copyright   (c) 2017-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Parser;

/**
 * A token from a serialized PHP value.
 */
final class Token
{
    const NULL = 'null';
    const BOOL = 'bool';
    const INTEGER = 'integer';
    const FLOAT = 'float';
    const STRING = 'string';
    const REFERENCE = 'reference';
    const ARRAY_START = 'array_start';
    const OBJECT_DEFAULT_NAME = 'object_default_name';
    const OBJECT_CUSTOM_NAME = 'object_custom_name';
    const OBJECT_CUSTOM_DATA = 'object_custom_data';
    const OBJECT_MEMBER_COUNT = 'object_member_count';
    const COMPOUND_END = 'compound_end'; // array or object close

    const PREFIX_NULL = 'N';
    const PREFIX_BOOL = 'b';
    const PREFIX_INTEGER = 'i';
    const PREFIX_FLOAT = 'd';
    const PREFIX_STRING = 's';
    const PREFIX_REFERENCE = 'R';
    const PREFIX_ARRAY_START = 'a';
    const PREFIX_OBJECT_DEFAULT_NAME = 'O';
    const PREFIX_OBJECT_CUSTOM_NAME = 'C';
    const PREFIX_COMPOUND_END = '}';

    /** @var string */
    private $type;

    /** @var string|null */
    private $value;

    public function __construct(
        string $type,
        ?string $value = null
    ) {
        $this->type = $type;
        $this->value = $value;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        $str = '';
        switch ($this->type) {
            case self::NULL:
                $str = self::PREFIX_NULL . ';';
                break;

            case self::BOOL:
                $str = self::PREFIX_BOOL . ':' . $this->value . ';';
                break;

            case self::INTEGER:
                $str = self::PREFIX_INTEGER . ':' . $this->value . ';';
                break;

            case self::FLOAT:
                $str = self::PREFIX_FLOAT . ':' . $this->value . ';';
                break;

            case self::STRING:
                $str = self::PREFIX_STRING . ':' . strlen($this->value) . ':"' . $this->value . '";';
                break;

            case self::REFERENCE:
                $str = self::PREFIX_REFERENCE . ':' . $this->value . ';';
                break;

            case self::ARRAY_START:
                $str = self::PREFIX_ARRAY_START . ':' . $this->value . ':{';
                break;

            case self::OBJECT_DEFAULT_NAME:
                $str = self::PREFIX_OBJECT_DEFAULT_NAME . ':' . strlen($this->value) . ':"' . $this->value . '":';
                break;

            case self::OBJECT_MEMBER_COUNT:
                $str = $this->value . ':{';
                break;

            case self::OBJECT_CUSTOM_NAME:
                $str = self::PREFIX_OBJECT_CUSTOM_NAME . ':' . strlen($this->value) . ':"' . $this->value . '":';
                break;

            case self::OBJECT_CUSTOM_DATA:
                $str = strlen($this->value) . ':{' . $this->value;
                break;

            case self::COMPOUND_END:
                $str = '}';
                break;
        }

        return $str;
    }
}