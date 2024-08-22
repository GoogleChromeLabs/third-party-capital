<?php
/**
 * Class GoogleChromeLabs\ThirdPartyCapital\Util\JsonDir
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Util;

/**
 * Static class providing the path to the directory containing JSON files.
 */
class JsonDir
{

    /**
     * Gets the absolute path to a file within the JSON directory.
     *
     * @param string $relativePath Relative path to the file within the JSON directory.
     * @return string Absolute path to the file.
     */
    public static function getFilePath(string $relativePath): string
    {
        $dir = dirname(__DIR__, 2) . '/data/';

        return $dir . ltrim($relativePath, '/');
    }
}
