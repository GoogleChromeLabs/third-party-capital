<?php
/**
 * Class GoogleChromeLabs\ThirdPartyCapital\ThirdParties\ThirdPartyBase
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\ThirdParties;

use GoogleChromeLabs\ThirdPartyCapital\Contracts\ThirdParty;
use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyData;
use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyDataFormatter;
use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyOutput;
use GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyScriptOutput;

/**
 * Base class representing a third party integration.
 */
abstract class ThirdPartyBase implements ThirdParty
{
    /**
     * Absolute path to the third party JSON file.
     *
     * @var string
     */
    private $jsonFilePath;

    /**
     * Input arguments.
     *
     * @var array<string, mixed>
     */
    private $args = [];

    /**
     * Third party data instance, lazily initialized.
     *
     * @var ?ThirdPartyData
     */
    private $data;

    /**
     * Third party output instance, lazily initialized.
     *
     * @var ?ThirdPartyOutput
     */
    private $output;

    /**
     * Constructor.
     *
     * @param array<string, mixed> $args Input arguments to set.
     */
    final public function __construct(array $args)
    {
        $this->jsonFilePath = $this->getJsonFilePath();

        $this->setArgs($args);
    }

    /**
     * Gets the third party identifier.
     *
     * @return string Third party identifier.
     */
    final public function getId(): string
    {
        $this->lazilyInitialize();

        return $this->output->getId();
    }

    /**
     * Sets input arguments for the integration.
     *
     * @param array<string, mixed> $args Input arguments to set.
     */
    final public function setArgs(array $args): void
    {
        $this->args = $args;

        // Reset third party output.
        $this->output = null;
    }

    /**
     * Gets the HTML output for the integration.
     *
     * Only relevant if the integration provides user-facing output.
     *
     * @return string HTML output, or empty string if not applicable.
     */
    final public function getHtml(): string
    {
        $this->lazilyInitialize();

        return $this->output->getHtml();
    }

    /**
     * Gets the stylesheet URLs for the integration.
     *
     * Only relevant if the integration provides stylesheets to use.
     *
     * @return string[] List of stylesheet URLs, or empty array if not applicable.
     */
    final public function getStylesheets(): array
    {
        $this->lazilyInitialize();

        return $this->output->getStylesheets();
    }

    /**
     * Gets the script definitions for the integration.
     *
     * Only relevant if the integration provides scripts to use.
     *
     * @return ThirdPartyScriptOutput[] List of script definition objects, or empty array if not applicable.
     */
    final public function getScripts(): array
    {
        $this->lazilyInitialize();

        return $this->output->getScripts();
    }

    /**
     * Gets the path to the third party data JSON file.
     *
     * @return string Absolute path to the JSON file.
     */
    abstract protected function getJsonFilePath(): string;

    /**
     * Lazily initializes the data and output instances, only if they aren't initialized yet.
     *
     * The data instance is only initialized once as it is agnostic to the input arguments.
     * The output instance needs to be reinitialized whenever the input arguments change.
     */
    private function lazilyInitialize(): void
    {
        if (! $this->data) {
            $this->data = ThirdPartyData::fromJsonFile($this->jsonFilePath);
        }

        if (! $this->output) {
            $this->output = ThirdPartyDataFormatter::formatData($this->data, $this->args);
        }
    }
}
