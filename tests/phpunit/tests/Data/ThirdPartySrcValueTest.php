<?php
/**
 * Tests for GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartySrcValue
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Tests;

use Exception;
use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartySrcValue;
use GoogleChromeLabs\ThirdPartyCapital\Exception\InvalidThirdPartyDataException;
use GoogleChromeLabs\ThirdPartyCapital\TestUtils\TestCase;

class ThirdPartySrcValueTest extends TestCase
{

    /**
     * @dataProvider dataGetMethods
     */
    public function testGetMethods(string $getMethod, array $args, $expected)
    {
        $this->runGetterTestCase(ThirdPartySrcValue::class, $getMethod, $args, $expected);
    }

    public function dataGetMethods()
    {
        return $this->gettersToTestCases([
            [
                'field'    => 'url',
                'getter'   => 'getUrl',
                'default'  => '',
                'required' => true,
            ],
            [
                'field'    => 'slugParam',
                'getter'   => 'getSlugParam',
                'default'  => '',
                'required' => false,
            ],
            [
                'field'    => 'params',
                'getter'   => 'getParams',
                'default'  => [],
                'required' => false,
            ],
        ], InvalidThirdPartyDataException::class);
    }

    public function testToArray()
    {
        $input = [
            'url'       => 'https://my-embed.com',
            'slugParam' => 'type',
            'params'    => ['id', 'mode'],
        ];
        $srcValue = new ThirdPartySrcValue($input);
        $this->assertSame($input, $srcValue->toArray());
    }
}
