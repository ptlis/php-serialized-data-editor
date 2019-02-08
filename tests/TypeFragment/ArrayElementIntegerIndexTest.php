<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Test\TypeFragment;

use PHPUnit\Framework\TestCase;
use ptlis\SerializedDataEditor\Type\StringType;
use ptlis\SerializedDataEditor\TypeFragment\ArrayElementIntegerIndex;

final class ArrayElementIntegerIndexTest extends TestCase
{
    public function testSerializeStringValue(): void
    {
        $element = new ArrayElementIntegerIndex(
            7,
            new StringType('bob')
        );

        $this->assertEquals('i:7;s:3:"bob";', strval($element));
    }

    public function testGetSetIndex(): void
    {
        $element = new ArrayElementIntegerIndex(
            7,
            new StringType('bob')
        );

        $element->setIndex(3);

        $this->assertEquals(3, $element->getIndex());
    }

    public function testGetSetValue(): void
    {
        $element = new ArrayElementIntegerIndex(
            7,
            new StringType('bob')
        );

        $element->setValue(new StringType('wibble'));

        $this->assertEquals(new StringType('wibble'), $element->getValue());
    }
}