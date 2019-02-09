<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Test\TypeFragment;

use PHPUnit\Framework\TestCase;
use ptlis\SerializedDataEditor\Type\StringType;
use ptlis\SerializedDataEditor\TypeFragment\ArrayElementStringIndex;

final class ArrayElementStringIndexTest extends TestCase
{
    public function testSerialize(): void
    {
        $element = new ArrayElementStringIndex(
            'foo',
            new StringType('bar')
        );

        $this->assertEquals('s:3:"foo";s:3:"bar";', strval($element));
    }

    public function testGetSetIndex(): void
    {
        $element = new ArrayElementStringIndex(
            'foo',
            new StringType('bar')
        );

        $element->setIndex('bat');

        $this->assertEquals('bat', $element->getIndex());
    }

    public function testGetSetValue(): void
    {
        $element = new ArrayElementStringIndex(
            'foo',
            new StringType('bar')
        );

        $element->setValue(new StringType('wibble'));

        $this->assertEquals(new StringType('wibble'), $element->getValue());
    }
}