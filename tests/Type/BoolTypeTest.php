<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Test\Type;

use PHPUnit\Framework\TestCase;
use ptlis\SerializedDataEditor\Type\BoolType;

final class BoolTypeTest extends TestCase
{
    public function testSerialize(): void
    {
        $trueType = new BoolType(true);
        $this->assertEquals('b:1;', strval($trueType));

        $falseType = new BoolType(false);
        $this->assertEquals('b:0;', strval($falseType));
    }

    public function testSetGet(): void
    {
        $trueType = new BoolType(true);

        $this->assertTrue($trueType->get());

        $trueType->set(false);

        $this->assertFalse($trueType->get());
    }

    public function testContainsStringCountPresent(): void
    {
        $type = new BoolType(true);

        $this->assertEquals(0, $type->containsStringCount('wibble'));
    }
}