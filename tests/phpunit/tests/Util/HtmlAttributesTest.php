<?php
/**
 * Tests for GoogleChromeLabs\ThirdPartyCapital\Util\HtmlAttributes
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Tests;

use GoogleChromeLabs\ThirdPartyCapital\Exception\NotFoundException;
use GoogleChromeLabs\ThirdPartyCapital\TestUtils\TestCase;
use GoogleChromeLabs\ThirdPartyCapital\Util\HtmlAttributes;

class HtmlAttributesTest extends TestCase
{

    public function testOffsetExistsWithPresentAttr()
    {
        $attrs = new HtmlAttributes(['class' => 'test-class']);
        $this->assertTrue(isset($attrs['class']));
        $this->assertFalse(isset($attrs['id']));
    }

    public function testOffsetExistsWithMissingAttr()
    {
        $attrs = new HtmlAttributes(['class' => 'test-class']);
        $this->assertFalse(isset($attrs['id']));
    }

    public function testOffsetGetWithPresentStringAttr()
    {
        $attrs = new HtmlAttributes(['class' => 'demo-class']);
        $this->assertSame('demo-class', $attrs['class']);
    }

    public function testOffsetGetWithPresentIntAttr()
    {
        $attrs = new HtmlAttributes(['min' => 3]);
        $this->assertSame('3', $attrs['min']); // Sanitized into string.
    }

    public function testOffsetGetWithPresentBoolAttr()
    {
        $attrs = new HtmlAttributes(['defer' => true]);
        $this->assertSame(true, $attrs['defer']);
    }

    public function testOffsetGetWithMissingAttr()
    {
        $this->expectException(NotFoundException::class);

        $attrs = new HtmlAttributes(['class' => 'demo-class']);
        $attrs['id'];
    }

    public function testOffsetSet()
    {
        $attrs = new HtmlAttributes(['class' => 'test-class']);
        $this->assertSame('test-class', $attrs['class']);

        // Class is read-only so setting shouldn't do anything.
        $attrs['class'] = 'another-class';
        $this->assertSame('test-class', $attrs['class']);
    }

    public function testOffsetUnset()
    {
        $attrs = new HtmlAttributes(['class' => 'demo-class']);
        $this->assertTrue(isset($attrs['class']));

        // Class is read-only so unsetting shouldn't do anything.
        unset($attrs['class']);
        $this->assertTrue(isset($attrs['class']));
    }

    public function testGetIterator()
    {
        $attrs = new HtmlAttributes([
            'id'    => 'unique-id',
            'class' => 'test-class',
        ]);
        $output = '';
        foreach ($attrs as $attr => $value) {
            $output .= "{$attr}:{$value};";
        }
        $this->assertSame('id:unique-id;class:test-class;', $output);
    }

    public function testToArray()
    {
        $input = [
            'id'    => 'unique-id',
            'class' => 'test-class',
        ];
        $attrs = new HtmlAttributes($input);
        $this->assertSame($input, $attrs->toArray());
    }

    /**
     * @dataProvider dataToString
     */
    public function testToString(array $input, string $expected)
    {
        $attrs = new HtmlAttributes($input);
        $this->assertSame($expected, (string) $attrs);
    }

    public function dataToString()
    {
        return [
            'regular'            => [
                [
                    'id'    => 'unique-id',
                    'class' => 'test-class',
                ],
                ' id="unique-id" class="test-class"',
            ],
            'with bool enabled'  => [
                [
                    'id'       => 'random-id',
                    'editable' => true,
                ],
                ' id="random-id" editable',
            ],
            'with bool disabled' => [
                [
                    'id'    => 'unique-id',
                    'defer' => false,
                ],
                ' id="unique-id"',
            ],
            'with bool mixed'    => [
                [
                    'id'    => 'unique-id',
                    'defer' => false,
                    'async' => true,
                    'class' => 'demo-class',
                ],
                ' id="unique-id" async class="demo-class"',
            ],
            'with null'          => [
                [
                    'id'     => 'some-id',
                    'width'  => null,
                    'height' => null,
                    'class'  => 'demo-class',
                ],
                ' id="some-id" class="demo-class"',
            ],
        ];
    }
}
