<?php
/**
 * Tests for GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyScriptOutput
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Tests;

use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyScriptData;
use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyScriptOutput;
use GoogleChromeLabs\ThirdPartyCapital\TestUtils\TestCase;

class ThirdPartyScriptOutputTest extends TestCase
{

    /**
     * @dataProvider dataGetMethods
     */
    public function testGetMethods(string $getMethod, array $args, $expected)
    {
        $this->runGetterTestCase(ThirdPartyScriptOutput::class, $getMethod, $args, $expected);
    }

    public function dataGetMethods()
    {
        return $this->gettersToTestCases([
            [
                'field'    => 'strategy',
                'getter'   => 'getStrategy',
                'default'  => '',
                'required' => false,
            ],
            [
                'field'    => 'location',
                'getter'   => 'getLocation',
                'default'  => '',
                'required' => false,
            ],
            [
                'field'    => 'action',
                'getter'   => 'getAction',
                'default'  => '',
                'required' => false,
            ],
            [
                'field'    => 'url',
                'getter'   => 'getUrl',
                'default'  => '',
                'required' => false,
            ],
            [
                'field'    => 'code',
                'getter'   => 'getCode',
                'default'  => '',
                'required' => false,
            ],
            [
                'field'    => 'key',
                'getter'   => 'getKey',
                'default'  => '',
                'required' => false,
            ],
        ]);
    }

    public function testToArray()
    {
        $input = [
            'strategy' => ThirdPartyScriptData::STRATEGY_CLIENT,
            'location' => ThirdPartyScriptData::LOCATION_HEAD,
            'action'   => ThirdPartyScriptData::ACTION_APPEND,
            'url'      => 'https://example.com/',
            'key'      => 'my-analytics',
        ];
        $scriptOutput = new ThirdPartyScriptOutput($input);
        $this->assertSame($input, $scriptOutput->toArray());
    }
}
