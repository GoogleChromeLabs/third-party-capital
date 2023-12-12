<?php
/**
 * Class GoogleChromeLabs\ThirdPartyCapital\ThirdParties\GoogleMapsEmbed
 *
 * @package   Third Party Capital
 * @copyright 2023 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\ThirdParties;

use GoogleChromeLabs\ThirdPartyCapital\Util\ThirdPartiesDir;

/**
 * Class representing the Google Maps Embed integration.
 */
class GoogleMapsEmbed extends ThirdPartyBase
{

    /**
     * Gets the path to the third party data JSON file.
     *
     * @return string Absolute path to the JSON file.
     */
    protected function getJsonFilePath(): string
    {
        return ThirdPartiesDir::getFilePath('google-maps-embed/data.json');
    }
}
