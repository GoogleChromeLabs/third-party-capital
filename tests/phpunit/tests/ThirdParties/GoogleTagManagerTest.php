<?php
/**
 * Tests for GoogleChromeLabs\ThirdPartyCapital\ThirdParties\GoogleTagManager
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Tests;

use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyScriptData;
use GoogleChromeLabs\ThirdPartyCapital\TestUtils\TestCase;
use GoogleChromeLabs\ThirdPartyCapital\ThirdParties\GoogleTagManager;

class GoogleTagManagerTest extends TestCase
{

    /**
     * Tests the output data for Google Tag Manager.
     *
     * This is effectively an integration test to ensure the JSON schema is handled correctly.
     *
     * @dataProvider dataOutput
     */
    public function testOutput(array $args, array $expectedScripts)
    {
        $gtm = new GoogleTagManager($args);
        $this->assertSame('google-tag-manager', $gtm->getId());
        $this->assertSame('', $gtm->getHtml());
        $this->assertSame([], $gtm->getStylesheets());
        $this->assertSame(
            $expectedScripts,
            array_map(
                static function ($script) {
                    return $script->toArray();
                },
                $gtm->getScripts()
            )
        );
    }

    public function dataOutput(): array
    {
        return [
            'basic example'          => [
                [ 'id' => 'GTM-12345678' ],
                [
                    [
                        'strategy' => ThirdPartyScriptData::STRATEGY_WORKER,
                        'location' => ThirdPartyScriptData::LOCATION_HEAD,
                        'action'   => ThirdPartyScriptData::ACTION_APPEND,
                        'url'      => 'https://www.googletagmanager.com/gtm.js?id=GTM-12345678',
                        'key'      => 'gtm',
                    ],
                    [
                        'strategy' => ThirdPartyScriptData::STRATEGY_WORKER,
                        'location' => ThirdPartyScriptData::LOCATION_HEAD,
                        'action'   => ThirdPartyScriptData::ACTION_APPEND,
                        'code'     => "window[\"dataLayer\"]=window[\"dataLayer\"]||[];window[\"dataLayer\"].push({'gtm.start':new Date().getTime(),event:'gtm.js'});",
                        'key'      => 'setup',
                    ],
                ],
            ],
            'with custom data layer' => [
                [
                    'id' => 'GTM-A1B2C3',
                    'l'  => 'myDataLayer1',
                ],
                [
                    [
                        'strategy' => ThirdPartyScriptData::STRATEGY_WORKER,
                        'location' => ThirdPartyScriptData::LOCATION_HEAD,
                        'action'   => ThirdPartyScriptData::ACTION_APPEND,
                        'url'      => 'https://www.googletagmanager.com/gtm.js?id=GTM-A1B2C3&l=myDataLayer1',
                        'key'      => 'gtm',
                    ],
                    [
                        'strategy' => ThirdPartyScriptData::STRATEGY_WORKER,
                        'location' => ThirdPartyScriptData::LOCATION_HEAD,
                        'action'   => ThirdPartyScriptData::ACTION_APPEND,
                        'code'     => "window[\"myDataLayer1\"]=window[\"myDataLayer1\"]||[];window[\"myDataLayer1\"].push({'gtm.start':new Date().getTime(),event:'gtm.js'});",
                        'key'      => 'setup',
                    ],
                ],
            ],
        ];
    }
}
