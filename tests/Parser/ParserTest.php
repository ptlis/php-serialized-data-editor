<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Test\Parser;

use PHPUnit\Framework\TestCase;
use ptlis\SerializedDataEditor\Parser\Parser;
use ptlis\SerializedDataEditor\Parser\Token;
use ptlis\SerializedDataEditor\Type\ArrayType;
use ptlis\SerializedDataEditor\Type\BoolType;
use ptlis\SerializedDataEditor\Type\FloatType;
use ptlis\SerializedDataEditor\Type\IntegerType;
use ptlis\SerializedDataEditor\Type\NullType;
use ptlis\SerializedDataEditor\Type\ReferenceType;
use ptlis\SerializedDataEditor\Type\StringType;
use ptlis\SerializedDataEditor\TypeFragment\ArrayElementIntegerIndex;
use ptlis\SerializedDataEditor\TypeFragment\ArrayElementStringIndex;

final class ParserTest extends TestCase
{
    public function testParseNullType(): void
    {
        $parser = new Parser();

        $nullType = $parser->parse([new Token(Token::NULL)]);
        $this->assertEquals(new NullType(), $nullType);
    }

    public function testParseBoolType(): void
    {
        $parser = new Parser();

        $trueType = $parser->parse([new Token(Token::BOOL, '1')]);
        $this->assertEquals(new BoolType(true), $trueType);

        $falseType = $parser->parse([new Token(Token::BOOL, '0')]);
        $this->assertEquals(new BoolType(false), $falseType);
    }

    public function testParseIntegerType(): void
    {
        $parser = new Parser();

        $positiveType = $parser->parse([new Token(Token::INTEGER, '173')]);
        $this->assertEquals(new IntegerType(173), $positiveType);

        $negativeType = $parser->parse([new Token(Token::INTEGER, '-98')]);
        $this->assertEquals(new IntegerType(-98), $negativeType);
    }

    public function testParseFloatType(): void
    {
        $parser = new Parser();

        $positiveType = $parser->parse([new Token(Token::FLOAT, '3.1415')]);
        $this->assertEquals(new FloatType(3.1415), $positiveType);

        $negativeType = $parser->parse([new Token(Token::FLOAT, '-581.34')]);
        $this->assertEquals(new FloatType(-581.34), $negativeType);
    }

    public function testParseStringType(): void
    {
        $parser = new Parser();

        $stringType = $parser->parse([new Token(Token::STRING, 'hello world!')]);
        $this->assertEquals(new StringType('hello world!'), $stringType);
    }

    public function testParseReferenceType(): void
    {
        $parser = new Parser();

        $referenceType = $parser->parse([new Token(Token::REFERENCE, '15')]);
        $this->assertEquals(new ReferenceType(15), $referenceType);
    }

    public function testParseFlatArrayTypeStringKeys(): void
    {
        $parser = new Parser();
        $arrayType = $parser->parse([
            new Token(Token::ARRAY_START, '2'),
            new Token(Token::STRING, 'foo'),
            new Token(Token::STRING, 'bar'),
            new Token(Token::STRING, 'baz'),
            new Token(Token::STRING, 'bat'),
            new Token(Token::COMPOUND_END)
        ]);

        $this->assertEquals(
            new ArrayType([
                new ArrayElementStringIndex('foo', new StringType('bar')),
                new ArrayElementStringIndex('baz', new StringType('bat'))
            ]),
            $arrayType
        );
    }

    public function testParseFlatArrayTypeIntegerKeys(): void
    {
        $parser = new Parser();
        $arrayType = $parser->parse([
            new Token(Token::ARRAY_START, '2'),
            new Token(Token::INTEGER, '0'),
            new Token(Token::STRING, 'foo'),
            new Token(Token::INTEGER, '1'),
            new Token(Token::STRING, 'bar'),
            new Token(Token::COMPOUND_END)
        ]);

        $this->assertEquals(
            new ArrayType([
                new ArrayElementIntegerIndex(0, new StringType('foo')),
                new ArrayElementIntegerIndex(1, new StringType('bar'))
            ]),
            $arrayType
        );
    }

    public function testParseNestedArrayType(): void
    {
        $parser = new Parser();
        $arrayType = $parser->parse([
            new Token(Token::ARRAY_START, '2'),
            new Token(Token::STRING, 'foo'),
            new Token(Token::STRING, 'bar'),
            new Token(Token::STRING, 'baz'),
            new Token(Token::ARRAY_START, '2'),
            new Token(Token::INTEGER, '0'),
            new Token(Token::STRING, 'hello'),
            new Token(Token::INTEGER, '1'),
            new Token(Token::STRING, 'world!'),
            new Token(Token::COMPOUND_END),
            new Token(Token::COMPOUND_END)
        ]);

        $this->assertEquals(
            new ArrayType([
                new ArrayElementStringIndex('foo', new StringType('bar')),
                new ArrayElementStringIndex(
                    'baz',
                    new ArrayType([
                        new ArrayElementIntegerIndex(0, new StringType('hello')),
                        new ArrayElementIntegerIndex(1, new StringType('world!'))
                    ])
                )
            ]),
            $arrayType
        );
    }
}