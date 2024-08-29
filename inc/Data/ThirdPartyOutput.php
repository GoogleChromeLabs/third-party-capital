<?php
/**
 * Class GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyOutput
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Data;

use GoogleChromeLabs\ThirdPartyCapital\Contracts\Arrayable;

/**
 * Class representing the output data for a third party.
 */
class ThirdPartyOutput implements Arrayable
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
     * HTML needed for the third party.
     *
     * @var string
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
     * @var ThirdPartyScriptOutput[]
     */
    private $scripts;

    /**
     * Constructor.
     *
     * @param array<string, mixed> $data Output data.
     */
    public function __construct(array $data)
    {
        $strFields = ['id', 'description', 'website', 'html'];
        foreach ($strFields as $field) {
            $this->$field = isset($data[ $field ]) ? (string) $data[ $field ] : '';
        }

        $to3pScript = static function ($scriptData) {
            return new ThirdPartyScriptOutput($scriptData);
        };

        $this->stylesheets = isset($data['stylesheets']) ? array_map('strval', $data['stylesheets']) : [];
        $this->scripts     = isset($data['scripts']) ? array_map($to3pScript, $data['scripts']) : [];
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
     * Gets the HTML needed for the third party.
     *
     * @return string HTML needed for the third party.
     */
    public function getHtml(): string
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
     * @return ThirdPartyScriptOutput[] Scripts needed for the third party.
     */
    public function getScripts(): array
    {
        return $this->scripts;
    }

    /**
     * Returns an array representation of the data.
     *
     * @return array<string, mixed> Associative array of data.
     */
    public function toArray(): array
    {
        $data = [
            'id'          => $this->id,
            'description' => $this->description,
        ];
        if ($this->website) {
            $data['website'] = $this->website;
        }
        if ($this->html) {
            $data['html'] = $this->html;
        }
        if ($this->stylesheets) {
            $data['stylesheets'] = $this->stylesheets;
        }
        if ($this->scripts) {
            $data['scripts'] = array_map(
                static function (ThirdPartyScriptOutput $scriptData) {
                    return $scriptData->toArray();
                },
                $this->scripts
            );
        }

        return $data;
    }
}
