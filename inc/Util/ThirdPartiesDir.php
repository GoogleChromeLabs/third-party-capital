<?php
/**
 * Class GoogleChromeLabs\ThirdPartyCapital\Util\ThirdPartiesDir
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Util;

/**
 * Static class providing the path to the 'third-parties' directory containing JSON files.
 */
class ThirdPartiesDir
{

    /**
     * Gets the absolute path to a file within the 'third-parties' directory.
     *
     * @param string $relativePath Relative path to the file within the 'third-parties' directory.
     * @return string Absolute path to the file.
     */
    public static function getFilePath(string $relativePath): string
    {
        $dir = dirname(__DIR__, 2) . '/src/third-parties/';

        return $dir . ltrim($relativePath, '/');
    }
}
