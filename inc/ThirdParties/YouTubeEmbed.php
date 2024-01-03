<?php
/**
 * Class GoogleChromeLabs\ThirdPartyCapital\ThirdParties\YouTubeEmbed
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2023 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\ThirdParties;

use GoogleChromeLabs\ThirdPartyCapital\Util\ThirdPartiesDir;

/**
 * Class representing the YouTube Embed integration.
 */
class YouTubeEmbed extends ThirdPartyBase
{

    /**
     * Gets the path to the third party data JSON file.
     *
     * @return string Absolute path to the JSON file.
     */
    protected function getJsonFilePath(): string
    {
        return ThirdPartiesDir::getFilePath('youtube-embed/data.json');
    }
}
