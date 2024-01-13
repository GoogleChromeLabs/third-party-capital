<?php
/**
 * Tests for GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyHtmlAttributes
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Tests;

use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyHtmlAttributes;
use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartySrcValue;
use GoogleChromeLabs\ThirdPartyCapital\TestUtils\TestCase;

class ThirdPartyHtmlAttributesTest extends TestCase
{

    public function testOffsetGetWithSrcValue()
    {
        $attrs = new ThirdPartyHtmlAttributes([
            'src' => [
                'url'    => 'https://embed-test.com',
                'params' => ['id'],
            ],
        ]);
        $this->assertInstanceOf(ThirdPartySrcValue::class, $attrs['src']);
        $this->assertSame('https://embed-test.com', $attrs['src']->getUrl());
        $this->assertSame(['id'], $attrs['src']->getParams());
    }

    public function testToStringWithSrcValue()
    {
        $attrs = new ThirdPartyHtmlAttributes([
            'id'    => 'test-unique-id',
            'src'   => [
                'url'    => 'https://embed-test.com',
                'params' => ['id'],
            ],
            'defer' => true,
        ]);
        $this->assertSame(
            ' id="test-unique-id" src="https://embed-test.com" defer',
            (string) $attrs
        );
    }
}
