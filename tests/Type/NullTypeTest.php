<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Test\Type;

use PHPUnit\Framework\TestCase;
use ptlis\SerializedDataEditor\Type\NullType;

final class NullTypeTest extends TestCase
{
    public function testSerialize(): void
    {
        $type = new NullType();

        $this->assertEquals('N;', strval($type));
    }

    public function testContainsStringCountPresent(): void
    {
        $type = new NullType();

        $this->assertEquals(0, $type->containsStringCount('wibble'));
    }

    public function testReplaceString(): void
    {
        $type = new NullType();

        $type->replaceString('wibble', 'wobble');

        $this->assertEquals('N;', strval($type));
    }
}