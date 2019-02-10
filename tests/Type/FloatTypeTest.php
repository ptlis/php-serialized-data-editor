<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Test\Type;

use PHPUnit\Framework\TestCase;
use ptlis\SerializedDataEditor\Type\FloatType;

final class FloatTypeTest extends TestCase
{
    public function testSerialize(): void
    {
        $positiveType = new FloatType(3.1415);
        $this->assertEquals('d:3.1415;', strval($positiveType));

        $negativeType = new FloatType(-111.55);
        $this->assertEquals('d:-111.55;', strval($negativeType));
    }

    public function testSetGet(): void
    {
        $type = new FloatType(5732.323);

        $this->assertEquals(5732.323, $type->get());

        $type->set(-12.5);

        $this->assertEquals(-12.5, $type->get());
    }

    public function testContainsStringCountPresent(): void
    {
        $type = new FloatType(5732.323);

        $this->assertEquals(0, $type->containsStringCount('wibble'));
    }
}