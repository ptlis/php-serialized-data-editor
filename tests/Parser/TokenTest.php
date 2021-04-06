<?php declare(strict_types=1);

/**
 * @copyright   (c) 2017-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Test\Parser;

use PHPUnit\Framework\TestCase;
use ptlis\SerializedDataEditor\Parser\Token;

final class TokenTest extends TestCase
{
    public function testSerializeNull(): void
    {
        $tokenList = [
            new Token(Token::NULL)
        ];
        $this->assertEquals('N;', join('', $tokenList));
    }

    public function testSerializeBool(): void
    {
        $tokenList = [
            new Token(Token::BOOL, '1')
        ];
        $this->assertEquals('b:1;', join('', $tokenList));
    }

    public function testSerializeInteger(): void
    {
        $tokenList = [
            new Token(Token::INTEGER, '132')
        ];
        $this->assertEquals('i:132;', join('', $tokenList));
    }

    public function testSerializeFloat(): void
    {
        $tokenList = [
            new Token(Token::FLOAT, '3.1459')
        ];
        $this->assertEquals('d:3.1459;', join('', $tokenList));
    }

    public function testSerializeString(): void
    {
        $tokenList = [
            new Token(Token::STRING, 'foobar')
        ];
        $this->assertEquals('s:6:"foobar";', join('', $tokenList));
    }

    public function testSerializeArray(): void
    {
        $tokenList = [
            new Token(Token::ARRAY_START, '2'),
            new Token(Token::INTEGER, '0'),
            new Token(Token::STRING, 'bar'),
            new Token(Token::INTEGER, '1'),
            new Token(Token::BOOL, '0'),
            new Token(Token::COMPOUND_END)
        ];
        $this->assertEquals('a:2:{i:0;s:3:"bar";i:1;b:0;}', join('', $tokenList));
    }

    public function testSerializeDefaultObject(): void
    {
        $tokenList = [
            new Token(Token::OBJECT_DEFAULT_NAME, 'stdClass'),
            new Token(Token::OBJECT_MEMBER_COUNT, '1'),
            new Token(Token::STRING, 'foo'),
            new Token(Token::STRING, 'bar'),
            new Token(Token::COMPOUND_END)
        ];
        $this->assertEquals('O:8:"stdClass":1:{s:3:"foo";s:3:"bar";}', join('', $tokenList));
    }

    public function testSerializeCustomObject(): void
    {
        $tokenList = [
            new Token(Token::OBJECT_CUSTOM_NAME, 'Foo'),
            new Token(Token::OBJECT_CUSTOM_DATA, '{"foo":"bar"}'),
            new Token(Token::COMPOUND_END)
        ];
        $this->assertEquals('C:3:"Foo":13:{{"foo":"bar"}}', join('', $tokenList));
    }

    public function testSerializeReferenceObject(): void
    {
        $tokenList = [
            new Token(Token::ARRAY_START, '2'),
            new Token(Token::INTEGER, '0'),
            new Token(Token::STRING, 'foo'),
            new Token(Token::INTEGER, '1'),
            new Token(Token::REFERENCE, '2'),
            new Token(Token::COMPOUND_END)
        ];
        $this->assertEquals('a:2:{i:0;s:3:"foo";i:1;R:2;}', join('', $tokenList));
    }
}