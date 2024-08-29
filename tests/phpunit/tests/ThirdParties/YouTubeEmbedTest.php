<?php
/**
 * Tests for GoogleChromeLabs\ThirdPartyCapital\ThirdParties\YouTubeEmbed
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Tests;

use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyScriptData;
use GoogleChromeLabs\ThirdPartyCapital\TestUtils\TestCase;
use GoogleChromeLabs\ThirdPartyCapital\ThirdParties\YouTubeEmbed;

class YouTubeEmbedTest extends TestCase
{

    /**
     * Tests the output data for YouTube Embed.
     *
     * This is effectively an integration test to ensure the JSON schema is handled correctly.
     *
     * @dataProvider dataOutput
     */
    public function testOutput(array $args, string $expectedHtml)
    {
        $yte = new YouTubeEmbed($args);
        $this->assertSame('youtube-embed', $yte->getId());
        $this->assertSame($expectedHtml, $yte->getHtml());
        $this->assertSame(
            ['https://cdn.jsdelivr.net/gh/paulirish/lite-youtube-embed@master/src/lite-yt-embed.css'],
            $yte->getStylesheets()
        );
        $this->assertSame(
            [
                [
                    'strategy' => ThirdPartyScriptData::STRATEGY_IDLE,
                    'location' => ThirdPartyScriptData::LOCATION_HEAD,
                    'action'   => ThirdPartyScriptData::ACTION_APPEND,
                    'url'      => 'https://cdn.jsdelivr.net/gh/paulirish/lite-youtube-embed@master/src/lite-yt-embed.js',
                    'key'      => 'lite-yt-embed',
                ],
            ],
            array_map(
                static function ($script) {
                    return $script->toArray();
                },
                $yte->getScripts()
            )
        );
    }

    public function dataOutput(): array
    {
        return [
            'basic example'    => [
                [
                    'videoid'   => 'ogfYd705cRs',
                    'playlabel' => 'Play: Keynote (Google I/O 2018)',
                ],
                $this->getHtmlString(
                    'lite-youtube',
                    [
                        'videoid'   => 'ogfYd705cRs',
                        'playlabel' => 'Play: Keynote (Google I/O 2018)',
                    ]
                ),
            ],
        ];
    }
}
