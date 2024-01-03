<?php
/**
 * Interface GoogleChromeLabs\ThirdPartyCapital\Contracts\ThirdParty
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2023 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Contracts;

use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyScriptOutput;

/**
 * Interface for a class representing a third party integration.
 */
interface ThirdParty
{

    /**
     * Sets input arguments for the integration.
     *
     * @param array $args Input arguments to set.
     */
    public function setArgs(array $args);

    /**
     * Gets the HTML output for the integration.
     *
     * Only relevant if the integration provides user-facing output.
     *
     * @return string HTML output, or empty string if not applicable.
     */
    public function getHtml(): string;

    /**
     * Gets the stylesheet URLs for the integration.
     *
     * Only relevant if the integration provides stylesheets to use.
     *
     * @return string[] List of stylesheet URLs, or empty array if not applicable.
     */
    public function getStylesheets(): array;

    /**
     * Gets the script definitions for the integration.
     *
     * Only relevant if the integration provides scripts to use.
     *
     * @return ThirdPartyScriptOutput[] List of script definition objects, or empty array if not applicable.
     */
    public function getScripts(): array;
}
