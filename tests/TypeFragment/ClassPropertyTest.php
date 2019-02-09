<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Test\TypeFragment;

use PHPUnit\Framework\TestCase;
use ptlis\SerializedDataEditor\Type\StringType;
use ptlis\SerializedDataEditor\TypeFragment\ClassProperty;

final class ClassPropertyTest extends TestCase
{
    public function testSerializePublic(): void
    {
        $public = new ClassProperty(ClassProperty::PUBLIC, 'Foo', 'publicProp', new StringType('foo'));

        $this->assertEquals(
            's:10:"publicProp";s:3:"foo";',
            strval($public)
        );
    }

    public function testSerializeProtected(): void
    {
        $protected = new ClassProperty(ClassProperty::PROTECTED, 'Foo', 'protectedProp', new StringType('bar'));

        $this->assertEquals(
            's:16:"' . "\0*\0" . 'protectedProp";s:3:"bar";',
            strval($protected)
        );
    }

    public function testSerializePrivate(): void
    {
        $private = new ClassProperty(ClassProperty::PRIVATE, 'Foo', 'privateProp', new StringType('bat'));

        $this->assertEquals(
            's:16:"' . "\0Foo\0" . 'privateProp";s:3:"bat";',
            strval($private)
        );
    }

    public function testGetSetVisibility(): void
    {
        $property = new ClassProperty(ClassProperty::PRIVATE, 'Foo', 'privateProp', new StringType('bat'));

        $property->setVisibility(ClassProperty::PUBLIC);

        $this->assertEquals(ClassProperty::PUBLIC, $property->getVisibility());
    }

    public function testGetSetClassName(): void
    {
        $property = new ClassProperty(ClassProperty::PRIVATE, 'Foo', 'privateProp', new StringType('bat'));

        $property->setClassName('TestClass');

        $this->assertEquals('TestClass', $property->getClassName());
    }

    public function testGetSetPropertyName(): void
    {
        $property = new ClassProperty(ClassProperty::PRIVATE, 'Foo', 'privateProp', new StringType('bat'));

        $property->setPropertyName('foobar');

        $this->assertEquals('foobar', $property->getPropertyName());
    }

    public function testGetSetValue(): void
    {
        $property = new ClassProperty(ClassProperty::PRIVATE, 'Foo', 'privateProp', new StringType('bat'));

        $property->setValue(new StringType('wibble'));

        $this->assertEquals(new StringType('wibble'), $property->getValue());
    }
}
