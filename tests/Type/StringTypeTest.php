<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Test\Type;

use PHPUnit\Framework\TestCase;
use ptlis\SerializedDataEditor\Type\StringType;

final class StringTypeTest extends TestCase
{
    public function testSerialize(): void
    {
        $type = new StringType('bob');
        $this->assertEquals('s:3:"bob";', strval($type));
    }

    public function testSetGet(): void
    {
        $type = new StringType('bob');

        $this->assertEquals('bob', $type->get());

        $type->set('wibble');

        $this->assertEquals('wibble', $type->get());
    }

    public function testContainsStringCountPresent(): void
    {
        $type = new StringType('wibble foo wibble');

        $this->assertEquals(2, $type->containsStringCount('wibble'));
    }

    public function testContainsStringCountAbsent(): void
    {
        $type = new StringType('foo bar');

        $this->assertEquals(0, $type->containsStringCount('wibble'));
    }
}