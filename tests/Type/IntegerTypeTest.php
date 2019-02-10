<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Test\Type;

use PHPUnit\Framework\TestCase;
use ptlis\SerializedDataEditor\Type\IntegerType;

final class IntegerTypeTest extends TestCase
{
    public function testSerialize(): void
    {
        $positiveType = new IntegerType(857);
        $this->assertEquals('i:857;', strval($positiveType));

        $negativeType = new IntegerType(-28);
        $this->assertEquals('i:-28;', strval($negativeType));
    }

    public function testSetGet(): void
    {
        $type = new IntegerType(58);

        $this->assertEquals(58, $type->get());

        $type->set(-555);

        $this->assertEquals(-555, $type->get());
    }

    public function testContainsStringCountPresent(): void
    {
        $type = new IntegerType(58);

        $this->assertEquals(0, $type->containsStringCount('wibble'));
    }
}