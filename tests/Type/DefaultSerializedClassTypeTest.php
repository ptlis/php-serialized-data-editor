<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Test\Type;

use PHPUnit\Framework\TestCase;
use ptlis\SerializedDataEditor\Type\DefaultSerializedClassType;
use ptlis\SerializedDataEditor\Type\StringType;
use ptlis\SerializedDataEditor\TypeFragment\ClassProperty;

final class DefaultSerializedClassTypeTest extends TestCase
{
    public function testSerialize(): void
    {
        $class = new DefaultSerializedClassType(
            'Foo',
            [
                new ClassProperty(ClassProperty::PUBLIC, 'Foo', 'publicProp', new StringType('foo')),
                new ClassProperty(ClassProperty::PROTECTED, 'Foo', 'protectedProp', new StringType('bar')),
                new ClassProperty(ClassProperty::PRIVATE, 'Foo', 'privateProp', new StringType('bat'))
            ]
        );

        $this->assertEquals(
            'O:3:"Foo":3:{s:10:"publicProp";s:3:"foo";s:16:"' . "\0" . '*' . "\0" . 'protectedProp";s:3:"bar";s:16:"' . "\0" . 'Foo' . "\0" . 'privateProp";s:3:"bat";}',
            strval($class)
        );
    }
}