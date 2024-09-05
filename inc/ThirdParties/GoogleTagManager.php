<?php
/**
 * Class GoogleChromeLabs\ThirdPartyCapital\ThirdParties\GoogleTagManager
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\ThirdParties;

use GoogleChromeLabs\ThirdPartyCapital\Util\JsonDir;

/**
 * Class representing the Google Tag Manager integration.
 */
class GoogleTagManager extends ThirdPartyBase
{

    /**
     * Gets the path to the third party data JSON file.
     *
     * @return string Absolute path to the JSON file.
     */
    protected function getJsonFilePath(): string
    {
        return JsonDir::getFilePath('google-tag-manager.json');
    }
}
