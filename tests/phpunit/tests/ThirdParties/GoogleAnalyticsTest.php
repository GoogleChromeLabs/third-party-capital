<?php
/**
 * Tests for GoogleChromeLabs\ThirdPartyCapital\ThirdParties\GoogleAnalytics
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Tests;

use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyScriptData;
use GoogleChromeLabs\ThirdPartyCapital\TestUtils\TestCase;
use GoogleChromeLabs\ThirdPartyCapital\ThirdParties\GoogleAnalytics;

class GoogleAnalyticsTest extends TestCase
{

    /**
     * Tests the output data for Google Analytics.
     *
     * This is effectively an integration test to ensure the JSON schema is handled correctly.
     *
     * @dataProvider dataOutput
     */
    public function testOutput(array $args, array $expectedScripts)
    {
        $ga = new GoogleAnalytics($args);
        $this->assertSame('google-analytics', $ga->getId());
        $this->assertSame('', $ga->getHtml());
        $this->assertSame([], $ga->getStylesheets());
        $this->assertSame(
            $expectedScripts,
            array_map(
                static function ($script) {
                    return $script->toArray();
                },
                $ga->getScripts()
            )
        );
    }

    public function dataOutput(): array
    {
        return [
            'basic example'          => [
                [ 'id' => 'G-12345678' ],
                [
                    [
                        'strategy' => ThirdPartyScriptData::STRATEGY_WORKER,
                        'location' => ThirdPartyScriptData::LOCATION_HEAD,
                        'action'   => ThirdPartyScriptData::ACTION_APPEND,
                        'url'      => 'https://www.googletagmanager.com/gtag/js?id=G-12345678',
                        'key'      => 'gtag',
                    ],
                    [
                        'strategy' => ThirdPartyScriptData::STRATEGY_WORKER,
                        'location' => ThirdPartyScriptData::LOCATION_HEAD,
                        'action'   => ThirdPartyScriptData::ACTION_APPEND,
                        'code'     => "window[\"dataLayer\"]=window[\"dataLayer\"]||[];window['gtag-'+\"dataLayer\"]=function (){window[\"dataLayer\"].push(arguments);};window['gtag-'+\"dataLayer\"]('js',new Date());window['gtag-'+\"dataLayer\"]('config',\"G-12345678\")",
                        'key'      => 'setup',
                    ],
                ],
            ],
            'with custom data layer' => [
                [
                    'id' => 'G-13579',
                    'l'  => 'myDataLayer1',
                ],
                [
                    [
                        'strategy' => ThirdPartyScriptData::STRATEGY_WORKER,
                        'location' => ThirdPartyScriptData::LOCATION_HEAD,
                        'action'   => ThirdPartyScriptData::ACTION_APPEND,
                        'url'      => 'https://www.googletagmanager.com/gtag/js?id=G-13579&l=myDataLayer1',
                        'key'      => 'gtag',
                    ],
                    [
                        'strategy' => ThirdPartyScriptData::STRATEGY_WORKER,
                        'location' => ThirdPartyScriptData::LOCATION_HEAD,
                        'action'   => ThirdPartyScriptData::ACTION_APPEND,
                        'code'     => "window[\"myDataLayer1\"]=window[\"myDataLayer1\"]||[];window['gtag-'+\"myDataLayer1\"]=function (){window[\"myDataLayer1\"].push(arguments);};window['gtag-'+\"myDataLayer1\"]('js',new Date());window['gtag-'+\"myDataLayer1\"]('config',\"G-13579\")",
                        'key'      => 'setup',
                    ],
                ],
            ],
        ];
    }
}
