<?php declare(strict_types=1);

/**
 * @copyright   (c) 2017-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\Tests;

use PHPUnit\Framework\TestCase;
use ptlis\SerializedDataEditor\Editor;

final class EditorTest extends TestCase
{
    public function testContainsCount(): void
    {
        $editor = new Editor();

        $this->assertEquals(
            2,
            $editor->containsCount(
                'O:3:"Foo":2:{s:3:"bar";s:6:"wibble";s:3:"baz";a:2:{i:0;s:6:"wibble";i:1;s:6:"wobble";}}',
                'wibble'
            )
        );
    }

    public function testContainsCountMultipleInString(): void
    {
        $editor = new Editor();

        $this->assertEquals(
            4,
            $editor->containsCount(
                'O:3:"Foo":3:{s:3:"bar";s:6:"wibble";s:3:"baz";a:2:{i:0;s:6:"wibble";i:1;s:6:"wobble";}s:3:"bat";a:1:{i:0;s:21:"wibble another wibble";}}',
                'wibble'
            )
        );
    }

    public function testReplace(): void
    {
        $editor = new Editor();

        $this->assertEquals(
            'O:3:"Foo":3:{s:3:"bar";s:6:"foobar";s:3:"baz";a:2:{i:0;s:6:"foobar";i:1;s:6:"wobble";}s:3:"bat";a:1:{i:0;s:21:"foobar another foobar";}}',
            $editor->replace(
                'O:3:"Foo":3:{s:3:"bar";s:6:"wibble";s:3:"baz";a:2:{i:0;s:6:"wibble";i:1;s:6:"wobble";}s:3:"bat";a:1:{i:0;s:21:"wibble another wibble";}}',
                'wibble',
                'foobar'
            )
        );
    }
}