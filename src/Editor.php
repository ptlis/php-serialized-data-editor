<?php declare(strict_types=1);

/**
 * @copyright   (c) 2017-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor;

use ptlis\SerializedDataEditor\Parser\Parser;
use ptlis\SerializedDataEditor\Parser\Token;
use ptlis\SerializedDataEditor\Parser\Tokenizer;

/**
 * Allows editing of serialized datastructures.
 */
final class Editor
{
    public function containsCount(
        string $serializedData,
        string $searchTerm
    ): int {
        $tokenList = (new Tokenizer())->tokenize($serializedData);
        $type = (new Parser())->parse($tokenList);

        return $type->containsStringCount($searchTerm);
    }

    public function replace(
        string $serializedData,
        string $searchTerm,
        string $replaceTerm
    ): string {
        $tokenList = (new Tokenizer())->tokenize($serializedData);
        $type = (new Parser())->parse($tokenList);
        $type->replaceString($searchTerm, $replaceTerm);

        return strval($type);
    }
}