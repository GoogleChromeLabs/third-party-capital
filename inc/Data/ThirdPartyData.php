<?php
/**
 * Class GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyData
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2023 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Data;

use GoogleChromeLabs\ThirdPartyCapital\Contracts\Arrayable;
use GoogleChromeLabs\ThirdPartyCapital\Exception\InvalidThirdPartyDataException;

/**
 * Class representing data for a third party.
 */
class ThirdPartyData implements Arrayable
{

    /**
     * Third party identifier.
     *
     * @var string
     */
    private $id;

    /**
     * Third party description.
     *
     * @var string
     */
    private $description;

    /**
     * Third party website, if provided.
     *
     * @var string
     */
    private $website;

    /**
     * HTML element needed for the third party.
     *
     * @var ThirdPartyHtmlData
     */
    private $html;

    /**
     * Stylesheets needed for the third party.
     *
     * @var string[]
     */
    private $stylesheets;

    /**
     * Scripts needed for the third party.
     *
     * @var ThirdPartyScriptData[]
     */
    private $scripts;

    /**
     * Constructor.
     *
     * @param array $data Data, e.g. from a third party JSON file.
     *
     * @throws InvalidThirdPartyDataException Thrown when provided data is invalid.
     */
    public function __construct(array $data)
    {
        if (! isset($data['id'])) {
            throw new InvalidThirdPartyDataException('Missing ID.');
        }
        if (! isset($data['description'])) {
            throw new InvalidThirdPartyDataException('Missing description.');
        }

        $strFields = array( 'id', 'description', 'website' );
        foreach ($strFields as $field) {
            $this->$field = isset($data[ $field ]) ? (string) $data[ $field ] : '';
        }

        $to3pScript = static function ($scriptData) {
            return new ThirdPartyScriptData($scriptData);
        };

        $this->html        = isset($data['html']) ? new ThirdPartyHtmlData($data['html']) : null;
        $this->stylesheets = isset($data['stylesheets']) ? array_map('strval', $data['stylesheets']) : array();
        $this->scripts     = isset($data['scripts']) ? array_map($to3pScript, $data['scripts']) : array();
    }

    /**
     * Gets the third party identifier.
     *
     * @return string Third party identifier.
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Gets the third party description.
     *
     * @return string Third party description.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Gets the third party website.
     *
     * @return string Third party website, if provided.
     */
    public function getWebsite(): string
    {
        return $this->website;
    }

    /**
     * Gets the HTML element needed for the third party.
     *
     * @return ThirdPartyHtmlData|null HTML element needed for the third party, or null.
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Gets the stylesheets needed for the third party.
     *
     * @return string[] Stylesheets needed for the third party.
     */
    public function getStylesheets(): array
    {
        return $this->stylesheets;
    }

    /**
     * Gets the scripts needed for the third party.
     *
     * @return ThirdPartyScriptData[] Scripts needed for the third party.
     */
    public function getScripts(): array
    {
        return $this->scripts;
    }

    /**
     * Returns an array representation of the data.
     *
     * @return array Associative array of data.
     */
    public function toArray(): array
    {
        $data = array(
            'id'          => $this->id,
            'description' => $this->description,
        );
        if ($this->website) {
            $data['website'] = $this->website;
        }
        if ($this->html) {
            $data['html'] = $this->html->toArray();
        }
        if ($this->stylesheets) {
            $data['stylesheets'] = $this->stylesheets;
        }
        if ($this->scripts) {
            $data['scripts'] = array_map(
                static function (ThirdPartyScriptData $scriptData) {
                    return $scriptData->toArray();
                },
                $this->scripts
            );
        }

        return $data;
    }

    /**
     * Creates a new instance from a JSON file with third party configuration data.
     *
     * @param string $file_path Absolute path to the JSON file.
     * @return ThirdPartyData Third party data instance based on the JSON data.
     */
    public static function fromJsonFile(string $file_path): ThirdPartyData
    {
        $data = json_decode(file_get_contents($file_path), true);
        return new self($data);
    }
}
