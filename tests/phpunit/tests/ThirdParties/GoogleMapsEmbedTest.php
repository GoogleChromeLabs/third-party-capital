<?php
/**
 * Tests for GoogleChromeLabs\ThirdPartyCapital\ThirdParties\GoogleMapsEmbed
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Tests;

use GoogleChromeLabs\ThirdPartyCapital\TestUtils\TestCase;
use GoogleChromeLabs\ThirdPartyCapital\ThirdParties\GoogleMapsEmbed;

class GoogleMapsEmbedTest extends TestCase
{

    /**
     * Tests the output data for Google Maps Embed.
     *
     * This is effectively an integration test to ensure the JSON schema is handled correctly.
     *
     * @dataProvider dataOutput
     */
    public function testOutput(array $args, string $expectedHtml)
    {
        $gme = new GoogleMapsEmbed($args);
        $this->assertSame('google-maps-embed', $gme->getId());
        $this->assertSame($expectedHtml, $gme->getHtml());
        $this->assertSame([], $gme->getStylesheets());
        $this->assertSame([], $gme->getScripts());
    }

    public function dataOutput(): array
    {
        return [
            'basic example'    => [
                [
                    'key' => 'MY_API_KEY',
                    'q'   => 'Space Needle, Seattle WA',
                ],
                $this->getHtmlString(
                    'iframe',
                    [
                        'loading'         => 'lazy',
                        'src'             => 'https://www.google.com/maps/embed/v1/place?key=MY_API_KEY&q=Space+Needle%2C+Seattle+WA',
                        'referrerpolicy'  => 'no-referrer-when-downgrade',
                        'frameborder'     => '0',
                        'style'           => 'border:0',
                        'allowfullscreen' => true,
                    ]
                ),
            ],
            'with custom mode' => [
                [
                    'mode'    => 'search',
                    'key'     => 'MY_API_KEY',
                    'q'       => 'tourist attractions in Seattle',
                    'maptype' => 'satellite',
                ],
                $this->getHtmlString(
                    'iframe',
                    [
                        'loading'         => 'lazy',
                        'src'             => 'https://www.google.com/maps/embed/v1/search?key=MY_API_KEY&q=tourist+attractions+in+Seattle&maptype=satellite',
                        'referrerpolicy'  => 'no-referrer-when-downgrade',
                        'frameborder'     => '0',
                        'style'           => 'border:0',
                        'allowfullscreen' => true,
                    ]
                ),
            ],
        ];
    }
}
