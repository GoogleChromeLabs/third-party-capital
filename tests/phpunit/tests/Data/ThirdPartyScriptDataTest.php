<?php
/**
 * Tests for GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyScriptData
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Tests;

use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyScriptData;
use GoogleChromeLabs\ThirdPartyCapital\Exception\InvalidThirdPartyDataException;
use GoogleChromeLabs\ThirdPartyCapital\TestUtils\TestCase;

class ThirdPartyScriptDataTest extends TestCase
{
    private $baseData = array(
        'strategy' => ThirdPartyScriptData::STRATEGY_CLIENT,
        'location' => ThirdPartyScriptData::LOCATION_HEAD,
        'action'   => ThirdPartyScriptData::ACTION_APPEND,
    );

    /**
     * @dataProvider dataGetMethods
     */
    public function testGetMethods(string $getMethod, array $args, $expected)
    {
        $this->runGetterTestCase(ThirdPartyScriptData::class, $getMethod, $args, $expected);
    }

    public function dataGetMethods()
    {
        return $this->gettersToTestCases([
            [
                'field'    => 'strategy',
                'getter'   => 'getStrategy',
                'value'    => ThirdPartyScriptData::STRATEGY_CLIENT,
                'default'  => '',
                'required' => true,
            ],
            [
                'field'    => 'location',
                'getter'   => 'getLocation',
                'value'    => ThirdPartyScriptData::LOCATION_HEAD,
                'default'  => '',
                'required' => true,
            ],
            [
                'field'    => 'action',
                'getter'   => 'getAction',
                'value'    => ThirdPartyScriptData::ACTION_APPEND,
                'default'  => '',
                'required' => true,
            ],
            [
                'field'    => 'key',
                'getter'   => 'getKey',
                'default'  => '',
                'required' => false,
            ],
            [
                'field'    => 'url',
                'getter'   => 'getUrl',
                'default'  => '',
                'required' => true,
            ], // Don't cover 'code' here as it can only be provided if 'url' is not provided. See separate test below.
            [
                'field'    => 'params',
                'getter'   => 'getParams',
                'default'  => [],
                'required' => false,
            ],
        ], InvalidThirdPartyDataException::class);
    }

    public function testConstructorWithUrlAndNoCode()
    {
        $data = ['url' => 'https://example.com/'];
        $scriptData = new ThirdPartyScriptData(array_merge($this->baseData, $data));

        $this->assertSame($data['url'], $scriptData->getUrl());
    }

    public function testConstructorWithCodeAndNoUrl()
    {
        $data = ['code' => 'window.dataLayer=window.dataLayer'];
        $scriptData = new ThirdPartyScriptData(array_merge($this->baseData, $data));

        $this->assertSame($data['code'], $scriptData->getCode());
    }

    public function testConstructorWithNoUrlAndNoCode()
    {
        $this->expectException(InvalidThirdPartyDataException::class);

        $scriptData = new ThirdPartyScriptData($this->baseData);
    }

    public function testConstructorWithUrlAndCode()
    {
        $this->expectException(InvalidThirdPartyDataException::class);

        $data = [
            'url'  => 'https://example.com/',
            'code' => 'window.dataLayer=window.dataLayer',
        ];
        $scriptData = new ThirdPartyScriptData(array_merge($this->baseData, $data));
    }

    /**
     * @dataProvider dataIsExternal
     */
    public function testIsExternal(array $data, bool $expected)
    {
        $scriptData = new ThirdPartyScriptData(array_merge($this->baseData, $data));
        if ($expected) {
            $this->assertTrue($scriptData->isExternal());
        } else {
            $this->assertFalse($scriptData->isExternal());
        }
    }

    public function dataIsExternal()
    {
        return [
            'with URL'  => [
                ['url' => 'https://example.com/'],
                true,
            ],
            'with code' => [
                ['code' => 'window.dataLayer=window.dataLayer'],
                false,
            ],
        ];
    }

    public function testToArray()
    {
        $input = array_merge(
            $this->baseData,
            [
                'url'    => 'https://example.com/',
                'key'    => 'my-analytics',
                'params' => ['id'],
            ]
        );
        $scriptData = new ThirdPartyScriptData($input);
        $this->assertSame($input, $scriptData->toArray());
    }
}
