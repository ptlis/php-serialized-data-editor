<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Parser;

use ptlis\SerializedDataEditor\Type\ArrayType;
use ptlis\SerializedDataEditor\Type\BoolType;
use ptlis\SerializedDataEditor\Type\FloatType;
use ptlis\SerializedDataEditor\Type\IntegerType;
use ptlis\SerializedDataEditor\Type\NullType;
use ptlis\SerializedDataEditor\Type\ObjectCustomSerializedType;
use ptlis\SerializedDataEditor\Type\ObjectDefaultSerializedType;
use ptlis\SerializedDataEditor\Type\ReferenceType;
use ptlis\SerializedDataEditor\Type\StringType;
use ptlis\SerializedDataEditor\Type\Type;
use ptlis\SerializedDataEditor\TypeFragment\ArrayElementIntegerIndex;
use ptlis\SerializedDataEditor\TypeFragment\ArrayElementStringIndex;
use ptlis\SerializedDataEditor\TypeFragment\ObjectProperty;

final class Parser
{
    /**
     * @param Token[] $tokenList
     * @return Type
     */
    public function parse(array $tokenList): Type
    {
        return $this->internalParse($tokenList);
    }

    /**
     * @param Token[] $tokenList
     * @param int $tokenOffset Tracks offset when iterating through token list.
     * @return Type
     */
    private function internalParse(array $tokenList, int &$tokenOffset = 0): Type
    {
        // Handle simple types
        if ($this->isSimpleType($tokenList[$tokenOffset])) {
            $type = $this->parseSimple($tokenList[$tokenOffset], $tokenOffset);

        // Handle complex types (arrays, objects)
        } else {
            $type = $this->parseComplex($tokenList, $tokenOffset);
        }

        return $type;
    }

    /**
     * Returns true if the token represents a simple type.
     */
    private function isSimpleType(Token $token): bool
    {
        return in_array(
            $token->getType(),
            [
                Token::NULL,
                Token::BOOL,
                Token::INTEGER,
                Token::FLOAT,
                Token::STRING,
                Token::REFERENCE
            ]
        );
    }

    /**
     * Parse a simple (single token) type.
     *
     * @param Token $token
     * @param int $tokenOffset Tracks offset when iterating through token list.
     * @return Type
     */
    public function parseSimple(Token $token, int &$tokenOffset): Type
    {
        switch ($token->getType()) {
            case Token::NULL:
                $type = new NullType();
                break;

            case Token::BOOL:
                $type = new BoolType('1' === $token->getValue() ? true : false);
                break;

            case Token::INTEGER:
                $type = new IntegerType(intval($token->getValue()));
                break;

            case Token::FLOAT:
                $type = new FloatType(floatval($token->getValue()));
                break;

            case Token::STRING:
                $type = new StringType($token->getValue());
                break;

            case Token::REFERENCE:
                $type = new ReferenceType(intval($token->getValue()));
                break;

            default:
                throw new \RuntimeException('Could not parse simple type "' . $token->getType() . '"');
        }

        $tokenOffset++;

        return $type;
    }

    /**
     * Parse a complex (multi token) type.
     *
     * @param Token[] $tokenList
     * @param int $tokenOffset Tracks offset when iterating through token list.
     * @return Type
     */
    private function parseComplex(array $tokenList, int &$tokenOffset): Type
    {
        switch ($tokenList[$tokenOffset]->getType()) {
            case Token::ARRAY_START:
                $type = $this->parseArray($tokenList, $tokenOffset);
                break;

            case Token::OBJECT_DEFAULT_NAME:
                $type = $this->parseObjectDefaultSerialization($tokenList, $tokenOffset);
                break;

            case Token::OBJECT_CUSTOM_NAME:
                $type = $this->parseObjectCustomSerialization($tokenList, $tokenOffset);
                break;

            default:
                throw new \RuntimeException('Could not parse complex type "' . $tokenList[$tokenOffset]->getType() . '"');
        }

        return $type;
    }

    /**
     * Parse an array type.
     *
     * @param Token[] $tokenList
     * @param int $tokenOffset Tracks offset when iterating through token list.
     * @return Type
     */
    private function parseArray(array $tokenList, int &$tokenOffset): Type
    {
        // Skip array open
        $tokenOffset++;

        /** @var Token $indexToken */
        $indexToken = null;

        // Iterate through tokens array elements
        $arrayElementList = [];
        while ($tokenOffset < count($tokenList)) {
            switch (true) {
                // Do nothing, we're done here
                case Token::COMPOUND_END === $tokenList[$tokenOffset]->getType():
                    $tokenOffset++;
                    break;

                // Array index
                case is_null($indexToken):
                    $indexToken = $tokenList[$tokenOffset];
                    $tokenOffset++;
                    break;

                // Element value
                case !is_null($indexToken):
                    $type = $this->internalParse($tokenList, $tokenOffset);

                    if (Token::INTEGER === $indexToken->getType()) {
                        $arrayElementList[] = new ArrayElementIntegerIndex(intval($indexToken->getValue()), $type);
                    } else {
                        $arrayElementList[] = new ArrayElementStringIndex($indexToken->getValue(), $type);
                    }

                    $indexToken = null;
                    break;
            }
        }

        return new ArrayType($arrayElementList);
    }

    /**
     * Parse a PHP-serialized object.
     *
     * @param Token[] $tokenList
     * @param int $tokenOffset Tracks offset when iterating through token list.
     * @return Type
     */
    public function parseObjectDefaultSerialization(array $tokenList, int &$tokenOffset): Type
    {
        $className = $tokenList[$tokenOffset]->getValue();

        // Skip object open and property count
        $tokenOffset += 2;

        /** @var Token $propertyNameToken */
        $propertyNameToken = null;

        // Iterate through tokens building object properties
        $propertyList = [];
        while ($tokenOffset < count($tokenList)) {
            switch (true) {
                // Do nothing, we're done here
                case Token::COMPOUND_END === $tokenList[$tokenOffset]->getType():
                    $tokenOffset++;
                    break;

                // Property name
                case is_null($propertyNameToken):
                    $propertyNameToken = $tokenList[$tokenOffset];
                    $tokenOffset++;
                    break;

                // Property Value
                case !is_null($propertyNameToken):
                    $propertyList[] = $this->parseProperty(
                        $className,
                        $propertyNameToken,
                        $this->internalParse($tokenList, $tokenOffset)
                    );

                    $propertyNameToken = null;
                    break;
            }
        }

        return new ObjectDefaultSerializedType(
            $className,
            $propertyList
        );
    }

    /**
     * Parse a property of a PHP-serialized object.
     */
    private function parseProperty(
        string $className,
        Token $propertyNameToken,
        Type $type
    ): ObjectProperty {

        // Default to public property
        $visibility = ObjectProperty::PUBLIC;
        $propertyName = $propertyNameToken->getValue();

        // Split property name on NUL character
        $parts = array_values(array_filter(explode("\0", $propertyNameToken->getValue())));

        // Protected or private property
        if (count($parts) > 1) {
            $propertyName = $parts[1];
            $visibility = ObjectProperty::PROTECTED;

            // Private property
            if ($className === $parts[0]) {
                $visibility = ObjectProperty::PRIVATE;
            }
        }

        return new ObjectProperty(
            $visibility,
            $className,
            $propertyName,
            $type
        );
    }

    /**
     * Parse a custom serialized object.
     *
     * @param Token[] $tokenList
     * @param int $tokenOffset Tracks offset when iterating through token list.
     * @return Type
     */
    public function parseObjectCustomSerialization(array $tokenList, int &$tokenOffset): Type
    {
        $className = $tokenList[$tokenOffset]->getValue();
        $tokenOffset++;

        $customSerializedData = $tokenList[$tokenOffset]->getValue();
        $tokenOffset++;

        // Skip end
        $tokenOffset++;

        return new ObjectCustomSerializedType($className, $customSerializedData);
    }
}