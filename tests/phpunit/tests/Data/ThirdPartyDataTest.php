<?php
/**
 * Tests for GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyData
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Tests;

use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyData;
use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyScriptData;
use GoogleChromeLabs\ThirdPartyCapital\Exception\InvalidThirdPartyDataException;
use GoogleChromeLabs\ThirdPartyCapital\TestUtils\TestCase;

class ThirdPartyDataTest extends TestCase
{

    /**
     * @dataProvider dataGetMethods
     */
    public function testGetMethods(string $getMethod, array $args, $expected)
    {
        $this->runGetterTestCase(ThirdPartyData::class, $getMethod, $args, $expected);
    }

    public function dataGetMethods()
    {
        return $this->gettersToTestCases([
            [
                'field'    => 'id',
                'getter'   => 'getId',
                'default'  => '',
                'required' => true,
            ],
            [
                'field'    => 'description',
                'getter'   => 'getDescription',
                'default'  => '',
                'required' => true,
            ],
            [
                'field'    => 'website',
                'getter'   => 'getWebsite',
                'default'  => '',
                'required' => false,
            ],
            [
                'field'    => 'html',
                'getter'   => 'getHtml',
                'value'    => [
                    'element'    => 'iframe',
                    'attributes' => [
                        'src'    => [
                            'url'    => 'https://example.com/my-video/',
                            'params' => ['v'],
                        ],
                        'width'  => '1920',
                        'height' => '1080',
                    ],
                ],
                'default'  => null,
                'required' => false,
            ],
            [
                'field'    => 'stylesheets',
                'getter'   => 'getStylesheets',
                'value'    => ['https://example.com/style.css', 'https://example.com/style-2.css'],
                'default'  => [],
                'required' => false,
            ],
            [
                'field'    => 'scripts',
                'getter'   => 'getScripts',
                'value'    => [
                    [
                        'strategy' => ThirdPartyScriptData::STRATEGY_CLIENT,
                        'location' => ThirdPartyScriptData::LOCATION_HEAD,
                        'action'   => ThirdPartyScriptData::ACTION_APPEND,
                        'url'      => 'https://example.com/',
                        'key'      => 'my-analytics',
                        'params'   => ['id'],
                    ],
                    [
                        'strategy' => ThirdPartyScriptData::STRATEGY_CLIENT,
                        'location' => ThirdPartyScriptData::LOCATION_HEAD,
                        'action'   => ThirdPartyScriptData::ACTION_APPEND,
                        'code'     => 'window.dataLayer=window.dataLayer',
                    ],
                ],
                'default'  => [],
                'required' => false,
            ],
        ], InvalidThirdPartyDataException::class);
    }

    public function testToArray()
    {
        $input = [
            'id'     => 'my-service',
            'description' => 'A service that allows embedding something.',
            'website'     => 'https://my-service.com/',
            'html'        => [
                'element'    => 'iframe',
                'attributes' => [
                    'src'    => [
                        'url'    => 'https://example.com/my-video/',
                        'params' => ['v'],
                    ],
                    'width'  => '1920',
                    'height' => '1080',
                ],
            ],
            'stylesheets' => ['https://example.com/style.css', 'https://example.com/style-2.css'],
        ];
        $data = new ThirdPartyData($input);
        $this->assertSame($input, $data->toArray());
    }
}
