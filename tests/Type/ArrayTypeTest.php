<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Test\Type;

use PHPUnit\Framework\TestCase;
use ptlis\SerializedDataEditor\Type\ArrayType;
use ptlis\SerializedDataEditor\Type\FloatType;
use ptlis\SerializedDataEditor\Type\StringType;
use ptlis\SerializedDataEditor\TypeFragment\ArrayElementIntegerIndex;
use ptlis\SerializedDataEditor\TypeFragment\ArrayElementStringIndex;

final class ArrayTypeTest extends TestCase
{
    public function testSerialize(): void
    {
        $type = new ArrayType([
            new ArrayElementStringIndex(
                'foo',
                new StringType('wibble')
            ),
            new ArrayElementIntegerIndex(
                7,
                new FloatType(3.1415)
            )
        ]);

        $this->assertEquals(
            'a:2:{s:3:"foo";s:6:"wibble";i:7;d:3.1415;}',
            strval($type)
        );
    }

    public function testContainsStringCountPresent(): void
    {
        $type = new ArrayType([
            new ArrayElementStringIndex(
                'foo',
                new StringType('wibble foo wibble')
            ),
            new ArrayElementIntegerIndex(
                7,
                new FloatType(3.1415)
            )
        ]);

        $this->assertEquals(2, $type->containsStringCount('wibble'));
    }

    public function testContainsStringCountAbsent(): void
    {
        $type = new ArrayType([
            new ArrayElementStringIndex(
                'foo',
                new StringType('bar')
            ),
            new ArrayElementIntegerIndex(
                7,
                new FloatType(3.1415)
            )
        ]);

        $this->assertEquals(0, $type->containsStringCount('wibble'));
    }

    public function testContainsStringCountKeyNotCounted(): void
    {
        $type = new ArrayType([
            new ArrayElementStringIndex(
                'wibble',
                new StringType('bar')
            ),
            new ArrayElementIntegerIndex(
                7,
                new FloatType(3.1415)
            )
        ]);

        $this->assertEquals(0, $type->containsStringCount('wibble'));
    }

    public function testReplaceString(): void
    {
        $type = new ArrayType([
            new ArrayElementStringIndex(
                'foo',
                new StringType('wibble foo wibble')
            ),
            new ArrayElementIntegerIndex(
                7,
                new FloatType(3.1415)
            )
        ]);

        $type->replaceString('wibble', 'wobble');

        $this->assertEquals(
            new ArrayType([
                new ArrayElementStringIndex(
                    'foo',
                    new StringType('wobble foo wobble')
                ),
                new ArrayElementIntegerIndex(
                    7,
                    new FloatType(3.1415)
                )
            ]),
            $type
        );
    }

    public function testReplaceStringKeyNotModified(): void
    {
        $type = new ArrayType([
            new ArrayElementStringIndex(
                'wibble',
                new StringType('foo')
            ),
            new ArrayElementIntegerIndex(
                7,
                new FloatType(3.1415)
            )
        ]);

        $type->replaceString('wibble', 'wobble');

        $this->assertEquals(
            new ArrayType([
                new ArrayElementStringIndex(
                    'wibble',
                    new StringType('foo')
                ),
                new ArrayElementIntegerIndex(
                    7,
                    new FloatType(3.1415)
                )
            ]),
            $type
        );
    }
}