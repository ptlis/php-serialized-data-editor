<?php declare(strict_types=1);

/**
 * @copyright   (c) 2017-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Test\Parser;

use PHPUnit\Framework\TestCase;
use ptlis\SerializedDataEditor\Parser\Token;
use ptlis\SerializedDataEditor\Parser\Tokenizer;

final class TokenizerTest extends TestCase
{
    public function testInvalidSerialization(): void {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid serialized data - unexpected character "t" encountered');

        $tokenizer = new Tokenizer();
        $tokenizer->tokenize('test invalid serialization');
    }

    public function testTokenizeNull(): void
    {
        $tokenizer = new Tokenizer();
        $tokenList = $tokenizer->tokenize('N;');

        $this->assertEquals(1, count($tokenList));
        $this->assertEquals(Token::NULL, $tokenList[0]->getType());
        $this->assertEquals(null, $tokenList[0]->getValue());
    }

    public function testTokenizeTrue(): void
    {
        $tokenizer = new Tokenizer();
        $tokenList = $tokenizer->tokenize('b:1;');

        $this->assertEquals(1, count($tokenList));
        $this->assertEquals(Token::BOOL, $tokenList[0]->getType());
        $this->assertEquals(1, $tokenList[0]->getValue());
    }

    public function testTokenizeFalse(): void
    {
        $tokenizer = new Tokenizer();
        $tokenList = $tokenizer->tokenize('b:0;');

        $this->assertEquals(1, count($tokenList));
        $this->assertEquals(Token::BOOL, $tokenList[0]->getType());
        $this->assertEquals(0, $tokenList[0]->getValue());
    }

    public function testTokenizeIntegerSingleDigit(): void
    {
        $tokenizer = new Tokenizer();
        $tokenList = $tokenizer->tokenize('i:7;');

        $this->assertEquals(1, count($tokenList));
        $this->assertEquals(Token::INTEGER, $tokenList[0]->getType());
        $this->assertEquals(7, $tokenList[0]->getValue());
    }

    public function testTokenizeIntegerMultipleDigits(): void
    {
        $tokenizer = new Tokenizer();
        $tokenList = $tokenizer->tokenize('i:1483;');

        $this->assertEquals(1, count($tokenList));
        $this->assertEquals(Token::INTEGER, $tokenList[0]->getType());
        $this->assertEquals(1483, $tokenList[0]->getValue());
    }

    public function testTokenizeFloat(): void
    {
        $tokenizer = new Tokenizer();
        $tokenList = $tokenizer->tokenize('d:3.1459;');

        $this->assertEquals(1, count($tokenList));
        $this->assertEquals(Token::FLOAT, $tokenList[0]->getType());
        $this->assertEquals(3.1459, $tokenList[0]->getValue());
    }

    public function testTokenizeSingleCharString(): void
    {
        $tokenizer = new Tokenizer();
        $tokenList = $tokenizer->tokenize('s:6:"foobar";');

        $this->assertEquals(1, count($tokenList));
        $this->assertEquals(Token::STRING, $tokenList[0]->getType());
        $this->assertEquals('foobar', $tokenList[0]->getValue());
    }

    public function testTokenizeMultiCharString(): void
    {
        $tokenizer = new Tokenizer();
        $tokenList = $tokenizer->tokenize('s:16:"foobar asdf test";');

        $this->assertEquals(1, count($tokenList));
        $this->assertEquals(Token::STRING, $tokenList[0]->getType());
        $this->assertEquals('foobar asdf test', $tokenList[0]->getValue());
    }

    public function testTokenizeArrayNumericIndexShort(): void
    {
        $tokenizer = new Tokenizer();
        $tokenList = $tokenizer->tokenize('a:3:{i:0;b:1;i:1;i:15;i:2;s:4:"test";}');

        $this->assertEquals(8, count($tokenList));

        $this->assertEquals(Token::ARRAY_START, $tokenList[0]->getType());
        $this->assertEquals(3, $tokenList[0]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[1]->getType());
        $this->assertEquals(0, $tokenList[1]->getValue());

        $this->assertEquals(Token::BOOL, $tokenList[2]->getType());
        $this->assertEquals(true, $tokenList[2]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[3]->getType());
        $this->assertEquals(1, $tokenList[2]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[4]->getType());
        $this->assertEquals(15, $tokenList[4]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[5]->getType());
        $this->assertEquals(2, $tokenList[5]->getValue());

        $this->assertEquals(Token::STRING, $tokenList[6]->getType());
        $this->assertEquals('test', $tokenList[6]->getValue());

        $this->assertEquals(Token::COMPOUND_END, $tokenList[7]->getType());
        $this->assertEquals(null, $tokenList[7]->getValue());
    }

    // Required to ensure we handle arrays with > 9 elements correctly
    public function testTokenizeArrayNumericIndexLong(): void
    {
        $tokenizer = new Tokenizer();
        $tokenList = $tokenizer->tokenize('a:11:{i:0;b:1;i:1;i:15;i:2;s:4:"test";i:3;i:234;i:4;s:1:"b";i:5;s:6:"wibble";i:6;i:1293;i:7;d:3.4;i:8;s:2:"hi";i:9;s:4:"woop";i:10;s:3:"bar";}');

        $this->assertEquals(24, count($tokenList));

        $this->assertEquals(Token::ARRAY_START, $tokenList[0]->getType());
        $this->assertEquals(11, $tokenList[0]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[1]->getType());
        $this->assertEquals(0, $tokenList[1]->getValue());

        $this->assertEquals(Token::BOOL, $tokenList[2]->getType());
        $this->assertEquals(true, $tokenList[2]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[3]->getType());
        $this->assertEquals(1, $tokenList[2]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[4]->getType());
        $this->assertEquals(15, $tokenList[4]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[5]->getType());
        $this->assertEquals(2, $tokenList[5]->getValue());

        $this->assertEquals(Token::STRING, $tokenList[6]->getType());
        $this->assertEquals('test', $tokenList[6]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[7]->getType());
        $this->assertEquals(3, $tokenList[7]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[8]->getType());
        $this->assertEquals(234, $tokenList[8]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[9]->getType());
        $this->assertEquals(4, $tokenList[9]->getValue());

        $this->assertEquals(Token::STRING, $tokenList[10]->getType());
        $this->assertEquals('b', $tokenList[10]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[11]->getType());
        $this->assertEquals(5, $tokenList[11]->getValue());

        $this->assertEquals(Token::STRING, $tokenList[12]->getType());
        $this->assertEquals('wibble', $tokenList[12]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[13]->getType());
        $this->assertEquals(6, $tokenList[13]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[14]->getType());
        $this->assertEquals(1293, $tokenList[14]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[15]->getType());
        $this->assertEquals(7, $tokenList[15]->getValue());

        $this->assertEquals(Token::FLOAT, $tokenList[16]->getType());
        $this->assertEquals(3.4, $tokenList[16]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[17]->getType());
        $this->assertEquals(8, $tokenList[17]->getValue());

        $this->assertEquals(Token::STRING, $tokenList[18]->getType());
        $this->assertEquals('hi', $tokenList[18]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[19]->getType());
        $this->assertEquals(9, $tokenList[19]->getValue());

        $this->assertEquals(Token::STRING, $tokenList[20]->getType());
        $this->assertEquals('woop', $tokenList[20]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[21]->getType());
        $this->assertEquals(10, $tokenList[21]->getValue());

        $this->assertEquals(Token::STRING, $tokenList[22]->getType());
        $this->assertEquals('bar', $tokenList[22]->getValue());

        $this->assertEquals(Token::COMPOUND_END, $tokenList[23]->getType());
        $this->assertEquals(null, $tokenList[23]->getValue());
    }

    public function testTokenizeStringIndex(): void
    {
        $tokenizer = new Tokenizer();
        $tokenList = $tokenizer->tokenize('a:2:{s:3:"foo";s:3:"bar";s:3:"baz";i:42;}');

        $this->assertEquals(6, count($tokenList));

        $this->assertEquals(Token::ARRAY_START, $tokenList[0]->getType());
        $this->assertEquals(2, $tokenList[0]->getValue());

        $this->assertEquals(Token::STRING, $tokenList[1]->getType());
        $this->assertEquals('foo', $tokenList[1]->getValue());

        $this->assertEquals(Token::STRING, $tokenList[2]->getType());
        $this->assertEquals('bar', $tokenList[2]->getValue());

        $this->assertEquals(Token::STRING, $tokenList[3]->getType());
        $this->assertEquals('baz', $tokenList[3]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[4]->getType());
        $this->assertEquals(42, $tokenList[4]->getValue());

        $this->assertEquals(Token::COMPOUND_END, $tokenList[5]->getType());
        $this->assertEquals(null, $tokenList[5]->getValue());
    }

    public function testTokenizeMultiDimensionalArray(): void
    {
        $tokenizer = new Tokenizer();
        $tokenList = $tokenizer->tokenize('a:2:{s:3:"foo";s:3:"bar";s:3:"baz";a:2:{i:0;i:3;i:1;s:3:"bat";}}');

        $this->assertEquals(11, count($tokenList));

        $this->assertEquals(Token::ARRAY_START, $tokenList[0]->getType());
        $this->assertEquals(2, $tokenList[0]->getValue());

        $this->assertEquals(Token::STRING, $tokenList[1]->getType());
        $this->assertEquals('foo', $tokenList[1]->getValue());

        $this->assertEquals(Token::STRING, $tokenList[2]->getType());
        $this->assertEquals('bar', $tokenList[2]->getValue());

        $this->assertEquals(Token::STRING, $tokenList[3]->getType());
        $this->assertEquals('baz', $tokenList[3]->getValue());

        $this->assertEquals(Token::ARRAY_START, $tokenList[4]->getType());
        $this->assertEquals(2, $tokenList[4]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[5]->getType());
        $this->assertEquals(0, $tokenList[5]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[6]->getType());
        $this->assertEquals(3, $tokenList[6]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[7]->getType());
        $this->assertEquals(1, $tokenList[7]->getValue());

        $this->assertEquals(Token::STRING, $tokenList[8]->getType());
        $this->assertEquals('bat', $tokenList[8]->getValue());

        $this->assertEquals(Token::COMPOUND_END, $tokenList[9]->getType());
        $this->assertEquals(null, $tokenList[9]->getValue());

        $this->assertEquals(Token::COMPOUND_END, $tokenList[10]->getType());
        $this->assertEquals(null, $tokenList[10]->getValue());
    }

    public function testEmptyObjectDefaultSerializer(): void
    {
        $tokenizer = new Tokenizer();
        $tokenList = $tokenizer->tokenize('O:8:"stdClass":0:{}');

        $this->assertEquals(3, count($tokenList));

        $this->assertEquals(Token::OBJECT_DEFAULT_NAME, $tokenList[0]->getType());
        $this->assertEquals('stdClass', $tokenList[0]->getValue());

        $this->assertEquals(Token::OBJECT_MEMBER_COUNT, $tokenList[1]->getType());
        $this->assertEquals(0, $tokenList[1]->getValue());

        $this->assertEquals(Token::COMPOUND_END, $tokenList[2]->getType());
        $this->assertEquals(null, $tokenList[2]->getValue());
    }

    public function testObjectDefaultSerializer(): void
    {
        $tokenizer = new Tokenizer();
        $tokenList = $tokenizer->tokenize('O:8:"stdClass":1:{s:3:"foo";s:3:"bar";}');

        $this->assertEquals(5, count($tokenList));

        $this->assertEquals(Token::OBJECT_DEFAULT_NAME, $tokenList[0]->getType());
        $this->assertEquals('stdClass', $tokenList[0]->getValue());

        $this->assertEquals(Token::OBJECT_MEMBER_COUNT, $tokenList[1]->getType());
        $this->assertEquals(1, $tokenList[1]->getValue());

        $this->assertEquals(Token::STRING, $tokenList[2]->getType());
        $this->assertEquals('foo', $tokenList[2]->getValue());

        $this->assertEquals(Token::STRING, $tokenList[3]->getType());
        $this->assertEquals('bar', $tokenList[3]->getValue());

        $this->assertEquals(Token::COMPOUND_END, $tokenList[4]->getType());
        $this->assertEquals(null, $tokenList[4]->getValue());
    }

    public function testObjectCustomSerializer(): void
    {
        $tokenizer = new Tokenizer();
        $tokenList = $tokenizer->tokenize('C:3:"Foo":13:{{"foo":"bar"}}');

        $this->assertEquals(3, count($tokenList));

        $this->assertEquals(Token::OBJECT_CUSTOM_NAME, $tokenList[0]->getType());
        $this->assertEquals('Foo', $tokenList[0]->getValue());

        $this->assertEquals(Token::OBJECT_CUSTOM_DATA, $tokenList[1]->getType());
        $this->assertEquals('{"foo":"bar"}', $tokenList[1]->getValue());

        $this->assertEquals(Token::COMPOUND_END, $tokenList[2]->getType());
        $this->assertEquals(null, $tokenList[2]->getValue());
    }

    public function testReference(): void {
        $tokenizer = new Tokenizer();
        $tokenList = $tokenizer->tokenize('a:2:{i:0;s:3:"foo";i:1;R:2;}');

        $this->assertEquals(6, count($tokenList));

        $this->assertEquals(Token::ARRAY_START, $tokenList[0]->getType());
        $this->assertEquals(2, $tokenList[0]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[1]->getType());
        $this->assertEquals(0, $tokenList[1]->getValue());

        $this->assertEquals(Token::STRING, $tokenList[2]->getType());
        $this->assertEquals('foo', $tokenList[2]->getValue());

        $this->assertEquals(Token::INTEGER, $tokenList[3]->getType());
        $this->assertEquals(1, $tokenList[3]->getValue());

        $this->assertEquals(Token::REFERENCE, $tokenList[4]->getType());
        $this->assertEquals(2, $tokenList[4]->getValue());

        $this->assertEquals(Token::COMPOUND_END, $tokenList[5]->getType());
        $this->assertEquals(null, $tokenList[5]->getValue());
    }
}