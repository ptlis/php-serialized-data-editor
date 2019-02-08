<?php declare(strict_types=1);

/**
 * @copyright   (c) 2017-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Tokenizer;

/**
 * Tokenizes serialized PHP data.
 *
 * See http://www.phpinternalsbook.com/classes_objects/serialization.html
 */
final class Tokenizer
{
    /**
     * Processes serialized data into an array of tokens.
     *
     * @param string $serializedData
     * @return Token[]
     */
    public function tokenize(string $serializedData): array
    {
        $tokenList = [];
        for ($i = 0; $i < strlen($serializedData); $i++) {
            $character = substr($serializedData, $i, 1);

            switch ($character) {
                // Null
                case Token::PREFIX_NULL:
                    $tokenList[] = new Token(Token::NULL);
                    $i++; // Skip terminator character ';'
                    break;

                // Boolean
                case Token::PREFIX_BOOL:
                    $tokenList[] = new Token(Token::BOOL, substr($serializedData, $i + 2, 1));
                    $i += 3;
                    break;

                // Integer
                case Token::PREFIX_INTEGER:
                    $tokenList[] = $this->getNumberToken(Token::INTEGER, $serializedData, $i);
                    break;

                // Float
                case Token::PREFIX_FLOAT:
                    $tokenList[] = $this->getNumberToken(Token::FLOAT, $serializedData, $i);
                    break;

                // String
                case Token::PREFIX_STRING:
                    $tokenList[] = $this->getStringToken($serializedData, $i);
                    break;

                // Reference
                case Token::PREFIX_REFERENCE:
                    $tokenList[] = $this->getNumberToken(Token::REFERENCE, $serializedData, $i);
                    break;

                // Array
                case Token::PREFIX_ARRAY_START:
                    $tokenList[] = $this->getArrayToken($serializedData, $i);
                    break;

                // Object, default serialization
                case Token::PREFIX_OBJECT_DEFAULT_NAME:
                    $tokenList = array_merge($tokenList, $this->getObjectDefaultToken($serializedData, $i));
                    break;

                // Object, custom serialization
                case Token::PREFIX_OBJECT_CUSTOM_NAME:
                    $tokenList = array_merge($tokenList, $this->getObjectCustomToken($serializedData, $i));
                    break;

                // Array or object end
                case Token::PREFIX_COMPOUND_END:
                    $tokenList[] = new Token(Token::COMPOUND_END);
                    break;

                default:
                    throw new \RuntimeException('Invalid serialized data - unexpected character "' . $character . '" encountered');
            }
        }

        return $tokenList;
    }

    /**
     * Create token for an integer or float.
     */
    private function getNumberToken(string $type, string $serializedData, int &$currentIndex): Token
    {
        // Skip first characters 'i:'
        $currentIndex += 2;

        // Get digits
        $number = '';
        while (substr($serializedData, $currentIndex, 1) !== ';') {
            $number .= substr($serializedData, $currentIndex, 1);
            $currentIndex++;
        }

        return new Token($type, $number);
    }

    /**
     * Create token for a string.
     */
    private function getStringToken(string $serializedData, int &$currentIndex): Token
    {
        // Skip first characters 's:'
        $currentIndex += 2;

        // Get string length
        $stringLength = '';
        while (substr($serializedData, $currentIndex, 1) !== ':') {
            $stringLength .= substr($serializedData, $currentIndex, 1);
            $currentIndex++;
        }

        // Skip separator and open quote ':"'
        $currentIndex += 2;

        // Read string
        $string = substr($serializedData, $currentIndex, intval($stringLength));
        $currentIndex += intval($stringLength);

        // Skip close terminator '";'
        $currentIndex++;

        return new Token(Token::STRING, $string);
    }

    /**
     * Create token for the start of an array.
     */
    private function getArrayToken(string $serializedData, int &$currentIndex): Token
    {
        // Skip first characters 'a:'
        $currentIndex += 2;

        // Get array length
        $arrayLength = '';
        while (substr($serializedData, $currentIndex, 1) !== ':') {
            $arrayLength .= substr($serializedData, $currentIndex, 1);
            $currentIndex++;
        }

        // Skip array open
        $currentIndex += 1;

        return new Token(Token::ARRAY_START, $arrayLength);
    }

    /**
     * Create tokens for the start of an object serialized with PHP's default serialization format.
     *
     * @return Token[]
     */
    private function getObjectDefaultToken(string $serializedData, int &$currentIndex): array
    {
        // Skip first characters 'O:'
        $currentIndex += 2;

        // Get class name
        $classNameLength = '';
        while (substr($serializedData, $currentIndex, 1) !== ':') {
            $classNameLength .= substr($serializedData, $currentIndex, 1);
            $currentIndex++;
        }

        // Skip separator and open quote ':"'
        $currentIndex += 2;

        // Read class name
        $className = substr($serializedData, $currentIndex, intval($classNameLength));
        $currentIndex += intval($classNameLength);

        // Skip closing quote and seperator '":'
        $currentIndex += 2;

        // Get object property count
        $objectPropertyCount = '';
        while (substr($serializedData, $currentIndex, 1) !== ':') {
            $objectPropertyCount .= substr($serializedData, $currentIndex, 1);
            $currentIndex++;
        }

        // Skip terminator
        $currentIndex++;

        return [
            new Token(Token::OBJECT_DEFAULT_NAME, $className),
            new Token(Token::OBJECT_MEMBER_COUNT, $objectPropertyCount)
        ];
    }

    /**
     * Create tokens for the start of an object serialized with a custom serialization format.
     *
     * @return Token[]
     */
    private function getObjectCustomToken(string $serializedData, int &$currentIndex): array
    {
        // Skip first characters 'O:'
        $currentIndex += 2;

        // Get class name
        $classNameLength = '';
        while (substr($serializedData, $currentIndex, 1) !== ':') {
            $classNameLength .= substr($serializedData, $currentIndex, 1);
            $currentIndex++;
        }

        // Skip separator and open quote ':"'
        $currentIndex += 2;

        // Read class name
        $className = substr($serializedData, $currentIndex, intval($classNameLength));
        $currentIndex += intval($classNameLength);

        // Skip closing quote and seperator '":'
        $currentIndex += 2;

        // Get length of serialized data
        $serializedDataLength = '';
        while (substr($serializedData, $currentIndex, 1) !== ':') {
            $serializedDataLength .= substr($serializedData, $currentIndex, 1);
            $currentIndex++;
        }

        // Skip seperator and opening delimiter ':{'
        $currentIndex += 2;

        // Read serialized data
        $data = substr($serializedData, $currentIndex, intval($serializedDataLength));
        $currentIndex += intval($serializedDataLength);

        // Skip closing delimiter '}'
        $currentIndex++;

        return [
            new Token(Token::OBJECT_CUSTOM_NAME, $className),
            new Token(Token::OBJECT_CUSTOM_DATA, $data),
            new Token(Token::COMPOUND_END)
        ];
    }
}