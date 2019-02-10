<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Test\Type;

use PHPUnit\Framework\TestCase;
use ptlis\SerializedDataEditor\Type\ReferenceType;

final class ReferenceTypeTest extends TestCase
{
    public function testSerialize(): void
    {
        $reference = new ReferenceType(1);

        $this->assertEquals('R:1;', strval($reference));
    }

    public function testGetSet(): void
    {
        $reference = new ReferenceType(1);

        $this->assertEquals(1, $reference->get());

        $reference->set(7);

        $this->assertEquals(7, $reference->get());
    }

    public function testContainsStringCountPresent(): void
    {
        $type = new ReferenceType(1);

        $this->assertEquals(0, $type->containsStringCount('wibble'));
    }
}