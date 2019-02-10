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
use ptlis\SerializedDataEditor\Type\ObjectCustomSerializedType;
use ptlis\SerializedDataEditor\Type\ObjectDefaultSerializedType;
use ptlis\SerializedDataEditor\Type\ReferenceType;
use ptlis\SerializedDataEditor\Type\StringType;
use ptlis\SerializedDataEditor\TypeFragment\ArrayElementIntegerIndex;
use ptlis\SerializedDataEditor\TypeFragment\ArrayElementStringIndex;
use ptlis\SerializedDataEditor\TypeFragment\ObjectProperty;

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

    public function testParseLargeArrayType(): void
    {
        $parser = new Parser();
        $arrayType = $parser->parse([
            new Token(Token::ARRAY_START, '11'),
            new Token(Token::INTEGER, '0'),
            new Token(Token::STRING, 'foo'),
            new Token(Token::INTEGER, '1'),
            new Token(Token::STRING, 'bar'),
            new Token(Token::INTEGER, '2'),
            new Token(Token::STRING, 'baz'),
            new Token(Token::INTEGER, '3'),
            new Token(Token::STRING, 'bat'),
            new Token(Token::INTEGER, '4'),
            new Token(Token::STRING, 'qux'),
            new Token(Token::INTEGER, '5'),
            new Token(Token::STRING, 'wibble'),
            new Token(Token::INTEGER, '6'),
            new Token(Token::STRING, 'wobble'),
            new Token(Token::INTEGER, '7'),
            new Token(Token::STRING, 'flob'),
            new Token(Token::INTEGER, '8'),
            new Token(Token::STRING, 'hello'),
            new Token(Token::INTEGER, '9'),
            new Token(Token::STRING, 'world!'),
            new Token(Token::INTEGER, '10'),
            new Token(Token::STRING, 'test'),
            new Token(Token::COMPOUND_END)
        ]);

        $this->assertEquals(
            new ArrayType([
                new ArrayElementIntegerIndex(0, new StringType('foo')),
                new ArrayElementIntegerIndex(1, new StringType('bar')),
                new ArrayElementIntegerIndex(2, new StringType('baz')),
                new ArrayElementIntegerIndex(3, new StringType('bat')),
                new ArrayElementIntegerIndex(4, new StringType('qux')),
                new ArrayElementIntegerIndex(5, new StringType('wibble')),
                new ArrayElementIntegerIndex(6, new StringType('wobble')),
                new ArrayElementIntegerIndex(7, new StringType('flob')),
                new ArrayElementIntegerIndex(8, new StringType('hello')),
                new ArrayElementIntegerIndex(9, new StringType('world!')),
                new ArrayElementIntegerIndex(10, new StringType('test'))
            ]),
            $arrayType
        );
    }

    public function testSimpleDefaultSerializedObject(): void
    {
        $parser = new Parser();
        $objectType = $parser->parse([
            new Token(Token::OBJECT_DEFAULT_NAME, 'stdClass'),
            new Token(Token::OBJECT_MEMBER_COUNT, '3'),
            new Token(Token::STRING, 'publicProp'),
            new Token(Token::STRING, 'foo'),
            new Token(Token::STRING, "\0" . '*' . "\0" . 'protectedProp'),
            new Token(Token::STRING, 'bar'),
            new Token(Token::STRING, "\0" . 'stdClass' . "\0" . 'privateProp'),
            new Token(Token::STRING, 'baz'),
            new Token(Token::COMPOUND_END)
        ]);

        $this->assertEquals(
            new ObjectDefaultSerializedType(
                'stdClass',
                [
                    new ObjectProperty(ObjectProperty::PUBLIC, 'stdClass', 'publicProp', new StringType('foo')),
                    new ObjectProperty(ObjectProperty::PROTECTED, 'stdClass', 'protectedProp', new StringType('bar')),
                    new ObjectProperty(ObjectProperty::PRIVATE, 'stdClass', 'privateProp', new StringType('baz'))
                ]
            ),
            $objectType
        );
    }

    public function testNestedDefaultSerializedObject(): void
    {
        $parser = new Parser();
        $objectType = $parser->parse([
            new Token(Token::OBJECT_DEFAULT_NAME, 'stdClass'),
            new Token(Token::OBJECT_MEMBER_COUNT, '2'),
            new Token(Token::STRING, 'foo'),
            new Token(Token::STRING, 'hello'),
            new Token(Token::STRING, 'bar'),
            new Token(Token::OBJECT_DEFAULT_NAME, 'stdClass'),
            new Token(Token::OBJECT_MEMBER_COUNT, '3'),
            new Token(Token::STRING, 'publicProp'),
            new Token(Token::STRING, 'foo'),
            new Token(Token::STRING, "\0" . '*' . "\0" . 'protectedProp'),
            new Token(Token::STRING, 'bar'),
            new Token(Token::STRING, "\0" . 'stdClass' . "\0" . 'privateProp'),
            new Token(Token::STRING, 'baz'),
            new Token(Token::COMPOUND_END),
            new Token(Token::COMPOUND_END)
        ]);

        $this->assertEquals(
            new ObjectDefaultSerializedType(
                'stdClass',
                [
                    new ObjectProperty(ObjectProperty::PUBLIC, 'stdClass', 'foo', new StringType('hello')),
                    new ObjectProperty(
                        ObjectProperty::PUBLIC,
                        'stdClass',
                        'bar',
                        new ObjectDefaultSerializedType(
                            'stdClass',
                            [
                                new ObjectProperty(ObjectProperty::PUBLIC, 'stdClass', 'publicProp', new StringType('foo')),
                                new ObjectProperty(ObjectProperty::PROTECTED, 'stdClass', 'protectedProp', new StringType('bar')),
                                new ObjectProperty(ObjectProperty::PRIVATE, 'stdClass', 'privateProp', new StringType('baz'))
                            ]
                        )
                    )
                ]
            ),
            $objectType
        );
    }

    public function testArrayInDefaultSerializationObjectType(): void
    {
        $parser = new Parser();
        $objectType = $parser->parse([
            new Token(Token::OBJECT_DEFAULT_NAME, 'stdClass'),
            new Token(Token::OBJECT_MEMBER_COUNT, '1'),
            new Token(Token::STRING, 'test'),
            new Token(Token::ARRAY_START, '2'),
            new Token(Token::INTEGER, '0'),
            new Token(Token::STRING, 'foo'),
            new Token(Token::INTEGER, '1'),
            new Token(Token::STRING, 'bar'),
            new Token(Token::COMPOUND_END),
            new Token(Token::COMPOUND_END)
        ]);

        $this->assertEquals(
            new ObjectDefaultSerializedType(
                'stdClass',
                [
                    new ObjectProperty(
                        ObjectProperty::PUBLIC,
                        'stdClass',
                        'test',
                        new ArrayType([
                            new ArrayElementIntegerIndex(0, new StringType('foo')),
                            new ArrayElementIntegerIndex(1, new StringType('bar'))
                        ])
                    )
                ]
            ),
            $objectType
        );
    }

    public function testDefaultSerializationObjectInArrayType(): void
    {
        $parser = new Parser();
        $arrayType = $parser->parse([
            new Token(Token::ARRAY_START, '2'),
            new Token(Token::INTEGER, '0'),
            new Token(Token::STRING, 'foo'),
            new Token(Token::INTEGER, '1'),
            new Token(Token::OBJECT_DEFAULT_NAME, 'stdClass'),
            new Token(Token::OBJECT_MEMBER_COUNT, '1'),
            new Token(Token::STRING, 'publicProp'),
            new Token(Token::STRING, 'bar'),
            new Token(Token::COMPOUND_END),
            new Token(Token::COMPOUND_END)
        ]);

        $this->assertEquals(
            new ArrayType([
                new ArrayElementIntegerIndex(0, new StringType('foo')),
                new ArrayElementIntegerIndex(
                    1,
                    new ObjectDefaultSerializedType(
                        'stdClass',
                        [
                            new ObjectProperty(ObjectProperty::PUBLIC, 'stdClass', 'publicProp', new StringType('bar'))
                        ]
                    )
                )
            ]),
            $arrayType
        );
    }

    public function testCustomSerializationObject(): void
    {
        $parser = new Parser();
        $objectType = $parser->parse([
            new Token(Token::OBJECT_CUSTOM_NAME, 'MyCustomClass'),
            new Token(Token::OBJECT_CUSTOM_DATA, '{"foo":"bar"}'),
            new Token(Token::COMPOUND_END)
        ]);

        $this->assertEquals(
            new ObjectCustomSerializedType('MyCustomClass', '{"foo":"bar"}'),
            $objectType
        );
    }

    public function testCustomSerializationObjectInArray(): void
    {
        $parser = new Parser();
        $objectType = $parser->parse([
            new Token(Token::ARRAY_START, '2'),
            new Token(Token::INTEGER, '0'),
            new Token(Token::STRING, 'foo'),
            new Token(Token::INTEGER, '1'),
            new Token(Token::OBJECT_CUSTOM_NAME, 'MyCustomClass'),
            new Token(Token::OBJECT_CUSTOM_DATA, '{"foo":"bar"}'),
            new Token(Token::COMPOUND_END),
            new Token(Token::COMPOUND_END)
        ]);

        $this->assertEquals(
            new ArrayType([
                new ArrayElementIntegerIndex(0, new StringType('foo')),
                new ArrayElementIntegerIndex(1, new ObjectCustomSerializedType('MyCustomClass', '{"foo":"bar"}'))
            ]),
            $objectType
        );
    }

    public function testAdjacentArrayTypes(): void
    {
        $parser = new Parser();
        $objectType = $parser->parse([
            new Token(Token::OBJECT_DEFAULT_NAME, 'Foo'),
            new Token(Token::OBJECT_MEMBER_COUNT, '3'),
            new Token(Token::STRING, 'bar'),
            new Token(Token::STRING, 'foobar'),
            new Token(Token::STRING, 'baz'),
            new Token(Token::ARRAY_START, '2'),
            new Token(Token::INTEGER, '0'),
            new Token(Token::STRING, 'foobar'),
            new Token(Token::INTEGER, '1'),
            new Token(Token::STRING, 'wibble'),
            new Token(Token::COMPOUND_END),
            new Token(Token::STRING, 'bat'),
            new Token(Token::ARRAY_START, '1'),
            new Token(Token::INTEGER, '0'),
            new Token(Token::STRING, 'foobar another foobar'),
            new Token(Token::COMPOUND_END),
            new Token(Token::COMPOUND_END)
        ]);

        $this->assertEquals(
            new ObjectDefaultSerializedType(
                'Foo',
                [
                    new ObjectProperty(ObjectProperty::PUBLIC, 'Foo', 'bar', new StringType('foobar')),
                    new ObjectProperty(
                        ObjectProperty::PUBLIC,
                        'Foo',
                        'baz',
                        new ArrayType([
                            new ArrayElementIntegerIndex(0, new StringType('foobar')),
                            new ArrayElementIntegerIndex(1, new StringType('wibble'))
                        ])
                    ),
                    new ObjectProperty(
                        ObjectProperty::PUBLIC,
                        'Foo',
                        'bat',
                        new ArrayType([
                            new ArrayElementIntegerIndex(0, new StringType('foobar another foobar')),
                        ])
                    )
                ]
            ),
            $objectType
        );
    }

    public function testAdjacentObjectTypes(): void
    {
        $parser = new Parser();
        $arrayType = $parser->parse([
            new Token(Token::ARRAY_START, '2'),

            new Token(Token::INTEGER, '0'),
            new Token(Token::OBJECT_DEFAULT_NAME, 'Foo'),
            new Token(Token::OBJECT_MEMBER_COUNT, '1'),
            new Token(Token::STRING, 'bar'),
            new Token(Token::STRING, 'foobar'),
            new Token(Token::COMPOUND_END),

            new Token(Token::INTEGER, '1'),
            new Token(Token::OBJECT_DEFAULT_NAME, 'Bar'),
            new Token(Token::OBJECT_MEMBER_COUNT, '1'),
            new Token(Token::STRING, 'bat'),
            new Token(Token::STRING, 'wibble'),
            new Token(Token::COMPOUND_END),

            new Token(Token::COMPOUND_END)
        ]);

        $this->assertEquals(
            new ArrayType([
                new ArrayElementIntegerIndex(
                    0,
                    new ObjectDefaultSerializedType(
                        'Foo',
                        [
                            new ObjectProperty(ObjectProperty::PUBLIC, 'Foo', 'bar', new StringType('foobar'))
                        ]
                    )
                ),

                new ArrayElementIntegerIndex(
                    1,
                    new ObjectDefaultSerializedType(
                        'Bar',
                        [
                            new ObjectProperty(ObjectProperty::PUBLIC, 'Bar', 'bat', new StringType('wibble'))
                        ]
                    )
                )
            ]),
            $arrayType
        );
    }
}