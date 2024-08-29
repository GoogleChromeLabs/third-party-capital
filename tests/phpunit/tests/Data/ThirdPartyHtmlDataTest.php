<?php
/**
 * Tests for GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyHtmlData
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Tests;

use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyHtmlData;
use GoogleChromeLabs\ThirdPartyCapital\Exception\InvalidThirdPartyDataException;
use GoogleChromeLabs\ThirdPartyCapital\TestUtils\TestCase;

class ThirdPartyHtmlDataTest extends TestCase
{

    /**
     * @dataProvider dataGetMethods
     */
    public function testGetMethods(string $getMethod, array $args, $expected)
    {
        $this->runGetterTestCase(ThirdPartyHtmlData::class, $getMethod, $args, $expected);
    }

    public function dataGetMethods()
    {
        return $this->gettersToTestCases([
            [
                'field'    => 'element',
                'getter'   => 'getElement',
                'default'  => '',
                'required' => true,
            ],
            [
                'field'    => 'attributes',
                'getter'   => 'getAttributes',
                'value'    => ['src' => 'https://example.com'],
                'default'  => [],
                'required' => true,
            ],
        ], InvalidThirdPartyDataException::class);
    }

    public function testToArray()
    {
        $input    = [
            'element'    => 'iframe',
            'attributes' => [
                'src'    => [
                    'url'    => 'https://example.com/my-video/',
                    'params' => ['v'],
                ],
                'width'  => '1920',
                'height' => '1080',
            ],
        ];
        $htmlData = new ThirdPartyHtmlData($input);
        $this->assertSame($input, $htmlData->toArray());
    }
}
