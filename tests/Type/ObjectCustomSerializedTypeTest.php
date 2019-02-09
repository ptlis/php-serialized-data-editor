<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Test\Type;

use PHPUnit\Framework\TestCase;
use ptlis\SerializedDataEditor\Type\ObjectCustomSerializedType;

final class ObjectCustomSerializedTypeTest extends TestCase
{
    public function testSerialize(): void
    {
        $object = new ObjectCustomSerializedType(
            'MyClass',
            '{"foo":"bar"}'
        );

        $this->assertEquals(
            'C:7:"MyClass":13:{{"foo":"bar"}}',
            strval($object)
        );
    }

    public function testGetSetClassName(): void
    {
        $object = new ObjectCustomSerializedType(
            'MyClass',
            '{"foo":"bar"}'
        );

        $this->assertEquals('MyClass', $object->getClassName());

        $object->setClassName('Test');

        $this->assertEquals('Test', $object->getClassName());
    }

    public function testGetSetSerializedData(): void
    {
        $object = new ObjectCustomSerializedType(
            'MyClass',
            '{"foo":"bar"}'
        );

        $this->assertEquals('{"foo":"bar"}', $object->getSerializedData());

        $object->setSerializedData('foo|bar');

        $this->assertEquals('foo|bar', $object->getSerializedData());
    }
}