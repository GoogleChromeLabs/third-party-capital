<?php
/**
 * Class GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyDataFormatter
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Data;

use GoogleChromeLabs\ThirdPartyCapital\Util\HtmlAttributes;

/**
 * Static class to format third party data.
 */
class ThirdPartyDataFormatter
{

    /**
     * Formats third party data for a given set of input arguments and returns the corresponding output.
     *
     * @see https://github.com/GoogleChromeLabs/third-party-capital/blob/54cd44d1bd197a7809ab2f6ede4d13a973087c3d/src/utils/index.ts#L105
     *
     * @param ThirdPartyData       $data Third party data to format.
     * @param array<string, mixed> $args Input arguments to format third party data with.
     * @return ThirdPartyOutput Third party output data.
     */
    public static function formatData(ThirdPartyData $data, array $args): ThirdPartyOutput
    {
        $htmlData    = $data->getHtml();
        $scriptsData = $data->getScripts();

        $allScriptParams = array_unique(
            array_reduce(
                $scriptsData,
                static function ($acc, ThirdPartyScriptData $scriptData) {
                    foreach ($scriptData->getParams() as $param) {
                        $acc[] = $param;
                    }
                    foreach (array_keys($scriptData->getOptionalParams()) as $param) {
                        $acc[] = $param;
                    }
                    return $acc;
                },
                []
            )
        );

        $scriptUrlParamInputs = self::intersectArgs($args, $allScriptParams);

        $htmlUrlParamInputs = [];
        $htmlSlugParamInput = [];
        if ($htmlData) {
            if (isset($htmlData->getAttributes()['src'])
                && $htmlData->getAttributes()['src'] instanceof ThirdPartySrcValue
            ) {
                $htmlUrlParamInputs = self::intersectArgs(
                    $args,
                    $htmlData->getAttributes()['src']->getParams()
                );
                $htmlSlugParamInput = self::intersectArgs(
                    $args,
                    [$htmlData->getAttributes()['src']->getSlugParam()]
                );
            }
        }

        $htmlAttrInputs = self::diffArgs(
            $args,
            array_keys(
                array_merge(
                    $scriptUrlParamInputs,
                    $htmlUrlParamInputs,
                    $htmlSlugParamInput
                )
            )
        );

        $newData = $data->toArray();
        if (isset($newData['html']) && $newData['html']) {
            $newData['html'] = self::formatHtml(
                $newData['html']['element'],
                $newData['html']['attributes'],
                $htmlAttrInputs,
                $htmlUrlParamInputs,
                $htmlSlugParamInput
            );
        }
        if (isset($newData['scripts']) && $newData['scripts']) {
            $newData['scripts'] = array_map(
                static function ($scriptData) use ($args) {
                    if (isset($scriptData['url'])) {
                        $scriptData['url'] = self::formatUrl(
                            $scriptData['url'],
                            $scriptData['params'] ?? [],
                            $args,
                            [],
                            $scriptData['optionalParams'] ?? []
                        );
                    } else {
                        $scriptData['code'] = self::formatCode(
                            $scriptData['code'],
                            $args,
                            $scriptData['optionalParams'] ?? []
                        );
                    }
                    // Params are irrelevant for formatted output.
                    unset($scriptData['params'], $scriptData['optionalParams']);
                    return $scriptData;
                },
                $newData['scripts']
            );
        }

        return new ThirdPartyOutput($newData);
    }

    /**
     * Formats the given HTML arguments into an HTML string.
     *
     * @see https://github.com/GoogleChromeLabs/third-party-capital/blob/54cd44d1bd197a7809ab2f6ede4d13a973087c3d/src/utils/index.ts#L66
     *
     * @param string               $element           Element tag name for the HTML element.
     * @param array<string, mixed> $attributes        Attributes for the HTML element.
     * @param array<string, mixed> $htmlAttrArgs      Input arguments for the HTML element attributes.
     * @param array<string, mixed> $urlQueryParamArgs Input arguments for the src attribute query parameters.
     * @param array<string, mixed> $slugParamArg      Optional. Input argument for the src attribute slug query
     *                                                parameter. Default empty array.
     * @return string HTML string.
     */
    public static function formatHtml(
        string $element,
        array $attributes,
        array $htmlAttrArgs,
        array $urlQueryParamArgs,
        array $slugParamArg = []
    ): string {
        if (! $attributes) {
            return "<{$element}></{$element}>";
        }

        if (isset($attributes['src']['url'])) {
            $attributes['src'] = self::formatUrl(
                $attributes['src']['url'],
                $attributes['src']['params'] ?? [],
                $urlQueryParamArgs,
                $slugParamArg
            );
        }

        // Overwrite default attributes with arguments as needed.
        foreach ($htmlAttrArgs as $name => $value) {
            $attributes[ $name ] = $value;
        }

        $htmlAttributes = new HtmlAttributes($attributes);
        return "<{$element}{$htmlAttributes}></{$element}>";
    }

    /**
     * Formats the given URL arguments into a URL string.
     *
     * @see https://github.com/GoogleChromeLabs/third-party-capital/blob/54cd44d1bd197a7809ab2f6ede4d13a973087c3d/src/utils/index.ts#L28
     *
     * @param string               $url            Base URL.
     * @param string[]             $params         Parameter names.
     * @param array<string, mixed> $args           Input arguments for the src attribute query parameters.
     * @param array<string, mixed> $slugParamArg   Optional. Input argument for the src attribute slug query parameter.
     *                                             Default empty array.
     * @param array<string, mixed> $optionalParams Optional. Optional parameter names and their defaults.
     *                                             Default empty array.
     * @return string HTML string.
     */
    public static function formatUrl(
        string $url,
        array $params,
        array $args,
        array $slugParamArg = [],
        array $optionalParams = []
    ): string {
        if ($slugParamArg) {
            $slug = array_values($slugParamArg)[0];

            $path = parse_url($url, PHP_URL_PATH);
            if ($path) {
                $trailingSlash = str_ends_with($path, '/') ? '/' : '';
                $url           = str_replace(
                    $path,
                    substr($path, 0, - strlen(basename($path) . $trailingSlash)) . $slug . $trailingSlash,
                    $url
                );
            } else {
                $url = rtrim($url, '/') . '/' . $slug;
            }
        }

        $queryArgs = [];
        if ($params && $args) {
            $queryArgs = self::intersectArgs($args, $params);
        }
        if ($optionalParams) {
            foreach ($optionalParams as $k => $v) {
                if (isset($args[$k])) {
                    $queryArgs[$k] = $args[$k];
                } elseif ($v) {
                    $queryArgs[$k] = $v;
                }
            }
        }

        if ($queryArgs) {
            $url = self::setUrlQueryArgs($url, $queryArgs);
        }

        return $url;
    }

    /**
     * Formats the given code arguments into a code string.
     *
     * @see https://github.com/GoogleChromeLabs/third-party-capital/blob/54cd44d1bd197a7809ab2f6ede4d13a973087c3d/src/utils/index.ts#L52
     *
     * @param string               $code           Code string with placeholders for URL query parameters.
     * @param array<string, mixed> $args           Input arguments for the src attribute query parameters.
     * @param array<string, mixed> $optionalParams Optional. Optional parameter names and their defaults.
     *                                             Default empty array.
     * @return string HTML string.
     */
    public static function formatCode(
        string $code,
        array $args,
        array $optionalParams = []
    ): string {
        // Conditionals.
        $code = preg_replace_callback(
            '/{{#([^{}]+?)}}(.*){{\/\1}}/',
            static function ($matches) use ($args, $optionalParams) {
                if ((isset($args[ $matches[1] ]) && $args[ $matches[1] ]) ||
                    (isset($optionalParams[ $matches[1] ]) &&  $optionalParams[ $matches[1] ])) {
                    return $matches[2];
                }
                return '';
            },
            $code
        );

        // Variables.
        return preg_replace_callback(
            '/{{([^}]+)}}/',
            static function ($matches) use ($args, $optionalParams) {
                if (isset($args[ $matches[1] ])) {
                    return json_encode($args[ $matches[1] ]);
                }
                if (isset($optionalParams[ $matches[1] ])) {
                    return json_encode($optionalParams[ $matches[1] ]);
                }
                return '""'; // The same as `json_encode('')`.
            },
            $code
        );
    }

    /**
     * Returns the subset of the given $args that refers to parameter within the given $params.
     *
     * @param array<string, mixed> $args   Input arguments.
     * @param string[]             $params Parameter names.
     * @return array<string, mixed> Intersection of $args based on $params.
     */
    private static function intersectArgs(array $args, array $params): array
    {
        return array_intersect_key($args, array_flip($params));
    }

    /**
     * Returns the subset of the given $args that refers to parameter not within the given $params.
     *
     * @param array<string, mixed> $args   Input arguments.
     * @param string[]             $params Parameter names.
     * @return array<string, mixed> Diff of $args based on $params.
     */
    private static function diffArgs(array $args, array $params): array
    {
        return array_diff_key($args, array_flip($params));
    }

    /**
     * Sets the given query $args on the given URL.
     *
     * @param string               $url  URL.
     * @param array<string, mixed> $args Input arguments for the URL query string.
     * @return string URL including query arguments.
     */
    private static function setUrlQueryArgs(string $url, array $args): string
    {
        if (! $args) {
            return $url;
        }

        $frag = strstr($url, '#');
        if ($frag) {
            $url = substr($url, 0, -strlen($frag));
        } else {
            $frag = '';
        }

        if (str_contains($url, '?')) {
            list( $url, $query ) = explode('?', $url, 2);
            $url                .= '?';
        } else {
            $url  .= '?';
            $query = '';
        }

        parse_str($query, $qs);
        $qs = self::urlencodeRecursive($qs);
        foreach ($args as $key => $value) {
            $qs[ $key ] = $value;
        }

        $query = http_build_query($qs);

        return ( $query ? $url . $query : rtrim($url, '?') ) . $frag;
    }

    /**
     * URL-encodes a value or a potentially nested array structure.
     *
     * @param mixed $value Scalar value or array to URL-encode.
     * @return mixed URL-encoded result.
     */
    private static function urlencodeRecursive($value)
    {
        if (is_array($value)) {
            foreach ($value as $index => $item) {
                $value[ $index ] = self::urlencodeRecursive($item);
            }
            return $value;
        }

        return urlencode($value);
    }
}
