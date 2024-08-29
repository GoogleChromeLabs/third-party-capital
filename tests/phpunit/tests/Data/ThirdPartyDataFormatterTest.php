<?php
/**
 * Tests for GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyDataFormatter
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Tests;

use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyData;
use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyDataFormatter;
use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyScriptData;
use GoogleChromeLabs\ThirdPartyCapital\TestUtils\TestCase;

class ThirdPartyDataFormatterTest extends TestCase
{

    /**
     * @dataProvider dataFormatData
     */
    public function testFormatData(array $input, array $args, array $expected)
    {
        $output = ThirdPartyDataFormatter::formatData(new ThirdPartyData($input), $args);
        $this->assertSame($expected, $output->toArray());
    }

    public function dataFormatData()
    {
        return [
            'minimum fields'              => [
                [
                    'id'          => 'a-useless-service',
                    'description' => 'This service cannot do anything. Nobody would do that in production.',
                ],
                [],
                [
                    'id'          => 'a-useless-service',
                    'description' => 'This service cannot do anything. Nobody would do that in production.',
                ],
            ],
            'basic example'               => [
                [
                    'id'          => 'my-service',
                    'description' => 'A service that allows embedding something.',
                    'website'     => 'https://my-service.com/',
                    'html'        => [
                        'element'    => 'iframe',
                        'attributes' => [
                            'src'    => 'https://example.com/my-video/',
                            'width'  => '1920',
                            'height' => '1080',
                        ],
                    ],
                    'stylesheets' => ['https://example.com/style.css', 'https://example.com/style-2.css'],
                ],
                [],
                [
                    'id'          => 'my-service',
                    'description' => 'A service that allows embedding something.',
                    'website'     => 'https://my-service.com/',
                    'html'        => '<iframe src="https://example.com/my-video/" width="1920" height="1080"></iframe>',
                    'stylesheets' => ['https://example.com/style.css', 'https://example.com/style-2.css'],
                ],
            ],
            'with HTML params'            => [
                [
                    'id'          => 'my-service',
                    'description' => 'A service that allows embedding something dynamic.',
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
                ],
                [
                    'v'       => '12345',
                    'loading' => 'lazy',
                ],
                [
                    'id'          => 'my-service',
                    'description' => 'A service that allows embedding something dynamic.',
                    'website'     => 'https://my-service.com/',
                    'html'        => '<iframe src="https://example.com/my-video/?v=12345" width="1920"'
                        . ' height="1080" loading="lazy"></iframe>',
                    'stylesheets' => ['https://example.com/style.css', 'https://example.com/style-2.css'],
                ],
            ],
            'with HTML slug param'        => [
                [
                    'id'          => 'my-service',
                    'description' => 'A service that allows embedding something with a slug param.',
                    'website'     => 'https://my-service.com/',
                    'html'        => [
                        'element'    => 'iframe',
                        'attributes' => [
                            'src'    => [
                                'url'       => 'https://example.com/design-pattern/blue/',
                                'slugParam' => 'color',
                                'params'    => ['id'],
                            ],
                            'width'  => '1920',
                            'height' => '1080',
                        ],
                    ],
                    'stylesheets' => ['https://example.com/style.css'],
                ],
                [
                    'id'              => '481',
                    'color'           => 'green',
                    'loading'         => 'lazy',
                    'allowfullscreen' => false,
                ],
                [
                    'id'          => 'my-service',
                    'description' => 'A service that allows embedding something with a slug param.',
                    'website'     => 'https://my-service.com/',
                    'html'        => '<iframe src="https://example.com/design-pattern/green/?id=481" width="1920"'
                        . ' height="1080" loading="lazy"></iframe>',
                    'stylesheets' => ['https://example.com/style.css'],
                ],
            ],
            'with script params'          => [
                [
                    'id'          => 'my-service',
                    'description' => 'A service that allows loading an Analytics script.',
                    'website'     => 'https://my-service.com/',
                    'scripts'     => [
                        [
                            'strategy' => ThirdPartyScriptData::STRATEGY_WORKER,
                            'location' => ThirdPartyScriptData::LOCATION_HEAD,
                            'action'   => ThirdPartyScriptData::ACTION_APPEND,
                            'url'      => 'https://example.com/analytics/',
                            'key'      => 'my-analytics',
                            'params'   => ['id', 'anonymizeIP', 'enhancedAttribution'],
                        ],
                        [
                            'strategy' => ThirdPartyScriptData::STRATEGY_WORKER,
                            'location' => ThirdPartyScriptData::LOCATION_HEAD,
                            'action'   => ThirdPartyScriptData::ACTION_APPEND,
                            'code'     => 'exampleAnalytics.init()',
                        ],
                    ],
                ],
                [
                    'id'              => '987123',
                    'anonymizeIP'     => 1,
                ],
                [
                    'id'          => 'my-service',
                    'description' => 'A service that allows loading an Analytics script.',
                    'website'     => 'https://my-service.com/',
                    'scripts'     => [
                        [
                            'strategy' => ThirdPartyScriptData::STRATEGY_WORKER,
                            'location' => ThirdPartyScriptData::LOCATION_HEAD,
                            'action'   => ThirdPartyScriptData::ACTION_APPEND,
                            'url'      => 'https://example.com/analytics/?id=987123&anonymizeIP=1',
                            'key'      => 'my-analytics',
                        ],
                        [
                            'strategy' => ThirdPartyScriptData::STRATEGY_WORKER,
                            'location' => ThirdPartyScriptData::LOCATION_HEAD,
                            'action'   => ThirdPartyScriptData::ACTION_APPEND,
                            'code'     => 'exampleAnalytics.init()',
                        ],
                    ],
                ],
            ],
        ];
    }

    public function testFormatHtmlWithoutAttributes()
    {
        $html = ThirdPartyDataFormatter::formatHtml('video', [], [], []);
        $this->assertSame('<video></video>', $html);
    }

    public function testFormatHtmlWithAttributes()
    {
        $html = ThirdPartyDataFormatter::formatHtml(
            'video',
            [
                'src'      => 'https://example.com/custom-video.mpg',
                'width'    => '1024',
                'height'   => '768',
                'controls' => true,
                'muted'    => false,
            ],
            [],
            []
        );
        $this->assertSame(
            '<video src="https://example.com/custom-video.mpg" width="1024" height="768" controls></video>',
            $html
        );
    }

    public function testFormatHtmlWithAttributesIncludingSrcValue()
    {
        $html = ThirdPartyDataFormatter::formatHtml(
            'iframe',
            [
                'src'      => [
                    'url'       => 'https://logo-service.com/embed/unicolor',
                    'slugParam' => 'kind',
                    'params'    => ['id', 'style', 'lang']
                ],
                'width'    => '1920',
                'height'   => '1080',
            ],
            [],
            [
                'id'   => '987654',
                'lang' => 'de',
            ],
            ['kind' => 'duotone']
        );
        $this->assertSame(
            '<iframe src="https://logo-service.com/embed/duotone?id=987654&lang=de" width="1920"'
                . ' height="1080"></iframe>',
            $html
        );
    }

    public function testFormatHtmlWithAttributesIncludingSrcValueAndAttributeArgs()
    {
        $html = ThirdPartyDataFormatter::formatHtml(
            'iframe',
            [
                'src'      => [
                    'url'       => 'https://example.com/embed/',
                    'params'    => ['id']
                ],
                'width'    => '1920',
                'height'   => '1080',
            ],
            [
                'loading' => 'lazy',
            ],
            [
                'id' => '23',
            ]
        );
        $this->assertSame(
            '<iframe src="https://example.com/embed/?id=23" width="1920" height="1080" loading="lazy"></iframe>',
            $html
        );
    }

    public function testFormatUrlWithNoParamsOrArgs()
    {
        $url = ThirdPartyDataFormatter::formatUrl('https://example.com/embed/', [], []);
        $this->assertSame('https://example.com/embed/', $url);
    }

    public function testFormatUrlWithParamsButNoArgs()
    {
        $url = ThirdPartyDataFormatter::formatUrl(
            'https://example.com/embed/',
            ['id', 'lang'],
            []
        );
        $this->assertSame('https://example.com/embed/', $url);
    }

    public function testFormatUrlWithParamsAndArgs()
    {
        $url = ThirdPartyDataFormatter::formatUrl(
            'https://example.com/embed/',
            ['id', 'direction', 'lang', 'style'],
            [
                'id'   => '8642',
                'lang' => 'es',
            ]
        );
        $this->assertSame('https://example.com/embed/?id=8642&lang=es', $url);
    }

    public function testFormatUrlWithSlugParamAndPathWithoutTrailingSlash()
    {
        $url = ThirdPartyDataFormatter::formatUrl(
            'https://example.com/embed/static',
            ['id'],
            [],
            ['mode' => 'interactive']
        );
        $this->assertSame('https://example.com/embed/interactive', $url);
    }

    public function testFormatUrlWithSlugParamAndPathWithTrailingSlash()
    {
        $url = ThirdPartyDataFormatter::formatUrl(
            'https://example.com/embed/static/',
            ['id'],
            [],
            ['mode' => 'interactive']
        );
        $this->assertSame('https://example.com/embed/interactive/', $url);
    }

    public function testFormatUrlWithSlugParamAndNoPath()
    {
        $url = ThirdPartyDataFormatter::formatUrl(
            'https://example.com',
            ['id'],
            [],
            ['mode' => 'interactive']
        );
        $this->assertSame('https://example.com/interactive', $url);
    }

    public function testFormatUrlWithQueryAndSlugParamAndParamsAndArgs()
    {
        $url = ThirdPartyDataFormatter::formatUrl(
            'https://example.com/embed/static?forcedParam=value',
            ['id'],
            ['id' => '12345'],
            ['mode' => 'interactive']
        );
        $this->assertSame('https://example.com/embed/interactive?forcedParam=value&id=12345', $url);
    }

    public function testFormatCodeWithoutArgs()
    {
        $code = ThirdPartyDataFormatter::formatCode(
            'document.querySelector("{{selector}}").addEventListener(api.{{callback}});',
            []
        );
        $this->assertSame(
            'document.querySelector("").addEventListener(api.);',
            $code
        );
    }

    public function testFormatCodeWithArgs()
    {
        $code = ThirdPartyDataFormatter::formatCode(
            'document.querySelector("{{selector}}").addEventListener(api.{{callback}});',
            [
                'selector' => '.my-cta-button',
                'callback' => 'addToCart',
            ]
        );
        $this->assertSame(
            'document.querySelector(".my-cta-button").addEventListener(api.addToCart);',
            $code
        );
    }

    public function testFormatCodeWithArgsIncorrectOrderAndTooMany()
    {
        $code = ThirdPartyDataFormatter::formatCode(
            'document.querySelector("{{selector}}").addEventListener(api.{{callback}});',
            [
                'callback' => 'addToCart',
                'device'   => 'phone',
                'selector' => '.my-cta-button',
            ]
        );
        $this->assertSame(
            'document.querySelector(".my-cta-button").addEventListener(api.addToCart);',
            $code
        );
    }
}
