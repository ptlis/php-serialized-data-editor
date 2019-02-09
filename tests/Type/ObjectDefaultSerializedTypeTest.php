<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Test\Type;

use PHPUnit\Framework\TestCase;
use ptlis\SerializedDataEditor\Type\ObjectDefaultSerializedType;
use ptlis\SerializedDataEditor\Type\StringType;
use ptlis\SerializedDataEditor\TypeFragment\ObjectProperty;

final class ObjectDefaultSerializedTypeTest extends TestCase
{
    public function testSerialize(): void
    {
        $object = new ObjectDefaultSerializedType(
            'Foo',
            [
                new ObjectProperty(ObjectProperty::PUBLIC, 'Foo', 'publicProp', new StringType('foo')),
                new ObjectProperty(ObjectProperty::PROTECTED, 'Foo', 'protectedProp', new StringType('bar')),
                new ObjectProperty(ObjectProperty::PRIVATE, 'Foo', 'privateProp', new StringType('bat'))
            ]
        );

        $this->assertEquals(
            'O:3:"Foo":3:{s:10:"publicProp";s:3:"foo";s:16:"' . "\0" . '*' . "\0" . 'protectedProp";s:3:"bar";s:16:"' . "\0" . 'Foo' . "\0" . 'privateProp";s:3:"bat";}',
            strval($object)
        );
    }
}