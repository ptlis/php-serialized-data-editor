<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Type;

use ptlis\SerializedDataEditor\Tokenizer\Token;

/**
 * Class representing a serialized boolean value.
 */
final class BoolType implements Type
{
    /** @var bool */
    private $value;


    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public function get(): bool
    {
        return $this->value;
    }

    public function set(bool $value): void
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return Token::PREFIX_BOOL . ':' . ($this->value ? '1' : '0') . ';';
    }
}