<?php declare(strict_types=1);

/**
 * @copyright   (c) 2017-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor;

use ptlis\SerializedDataEditor\Parser\Token;
use ptlis\SerializedDataEditor\Parser\Tokenizer;

/**
 * Allows editing of serialized datastructures.
 */
final class Editor
{
    public function contains(
        string $serializedData,
        string $searchTerm
    ): bool {
        $tokenizer = new Tokenizer();

        $containsString = false;
        foreach ($tokenizer->tokenize($serializedData) as $token) {
            if (Token::STRING === $token->getType() && strstr($token->getValue(), $searchTerm)) {
                $containsString = true;
            }
        }

        return $containsString;
    }

    public function containsCount(
        string $serializedData,
        string $searchTerm
    ): int {
        $tokenizer = new Tokenizer();

        $count = 0;
        foreach ($tokenizer->tokenize($serializedData) as $token) {
            if (Token::STRING === $token->getType() && strstr($token->getValue(), $searchTerm)) {
                $count += substr_count($token->getValue(), $searchTerm);
            }
        }

        return $count;
    }

    public function replace(
        string $serializedData,
        string $searchTerm,
        string $replaceTerm
    ): string {
        $tokenizer = new Tokenizer();

        $tokenList = $tokenizer->tokenize($serializedData);
        foreach ($tokenList as $index => $token) {
            if (Token::STRING === $token->getType()) {
                $tokenList[$index] = new Token(
                    Token::STRING,
                    str_replace($searchTerm, $replaceTerm, $token->getValue())
                );
            }
        }

        return join('', $tokenList);
    }
}