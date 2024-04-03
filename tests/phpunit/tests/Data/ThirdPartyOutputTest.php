<?php
/**
 * Tests for GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyOutput
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Tests;

use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyOutput;
use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyScriptData;
use GoogleChromeLabs\ThirdPartyCapital\TestUtils\TestCase;

class ThirdPartyOutputTest extends TestCase
{

    /**
     * @dataProvider dataGetMethods
     */
    public function testGetMethods(string $getMethod, array $args, $expected)
    {
        $this->runGetterTestCase(ThirdPartyOutput::class, $getMethod, $args, $expected);
    }

    public function dataGetMethods()
    {
        return $this->gettersToTestCases([
            [
                'field'    => 'id',
                'getter'   => 'getId',
                'default'  => '',
                'required' => false,
            ],
            [
                'field'    => 'description',
                'getter'   => 'getDescription',
                'default'  => '',
                'required' => false,
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
                'default'  => '',
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
                        'url'      => 'https://example.com/?id=12345789',
                        'key'      => 'my-analytics',
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
        ]);
    }

    public function testToArray()
    {
        $input = [
            'id'          => 'my-service',
            'description' => 'A service that allows embedding something.',
            'website'     => 'https://my-service.com/',
            'html'        => '<iframe src="https://example.com/my-video/?v=13579" width="1920" height="1080"></iframe>',
            'stylesheets' => ['https://example.com/style.css', 'https://example.com/style-2.css'],
        ];
        $output = new ThirdPartyOutput($input);
        $this->assertSame($input, $output->toArray());
    }
}
