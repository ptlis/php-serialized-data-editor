<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Type;

use ptlis\SerializedDataEditor\Parser\Token;

/**
 * Class representing a serialized string value.
 */
final class StringType implements Type
{
    /** @var string */
    private $value;


    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function get(): string
    {
        return $this->value;
    }

    public function set(string $value): void
    {
        $this->value = $value;
    }

    public function containsStringCount(string $searchTerm): int
    {
        return substr_count($this->value, $searchTerm);
    }

    public function replaceString(string $searchTerm, string $replaceTerm): void
    {
        $this->value = str_replace($searchTerm, $replaceTerm, $this->value);
    }

    public function __toString(): string
    {
        return Token::PREFIX_STRING . ':' . strlen($this->value) . ':"' . $this->value . '";';
    }
}