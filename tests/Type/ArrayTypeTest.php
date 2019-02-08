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
    public function testSerialize()
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
}