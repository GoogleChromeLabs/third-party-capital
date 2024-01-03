<?php
/**
 * Class GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyHtmlData
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Data;

use GoogleChromeLabs\ThirdPartyCapital\Contracts\Arrayable;
use GoogleChromeLabs\ThirdPartyCapital\Exception\InvalidThirdPartyDataException;

/**
 * Class representing data for a third party HTML element.
 */
class ThirdPartyHtmlData implements Arrayable
{

    /**
     * Element tag name for the HTML element.
     *
     * @var string
     */
    private $element;

    /**
     * Attributes for the HTML element.
     *
     * @var ThirdPartyHtmlAttributes
     */
    private $attributes;

    /**
     * Constructor.
     *
     * @param array $htmlData HTML data, e.g. from a third party JSON file.
     *
     * @throws InvalidThirdPartyDataException Thrown when provided HTML data is invalid.
     */
    public function __construct(array $htmlData)
    {
        $this->validateData($htmlData);
        $this->setData($htmlData);
    }

    /**
     * Gets the element tag name for the HTML element.
     *
     * @return string Element tag name for the HTML element.
     */
    public function getElement(): string
    {
        return $this->element;
    }

    /**
     * Gets the attributes for the HTML element.
     *
     * @return ThirdPartyHtmlAttributes Attributes for the HTML element.
     */
    public function getAttributes(): ThirdPartyHtmlAttributes
    {
        return $this->attributes;
    }

    /**
     * Validates the given HTML data.
     *
     * @param array $htmlData HTML data, e.g. from a third party JSON file.
     *
     * @throws InvalidThirdPartyDataException Thrown when provided HTML data is invalid.
     */
    private function validateData(array $htmlData)
    {
        if (! isset($htmlData['element'])) {
            throw new InvalidThirdPartyDataException('Missing HTML element.');
        }
        if (! isset($htmlData['attributes'])) {
            throw new InvalidThirdPartyDataException('Missing HTML attributes.');
        }
        if (! isset($htmlData['attributes']['src'])) {
            throw new InvalidThirdPartyDataException('Missing HTML src attribute.');
        }
    }

    /**
     * Sets the given HTML data.
     *
     * @param array $htmlData HTML data, e.g. from a third party JSON file.
     */
    private function setData(array $htmlData)
    {
        $this->element    = (string) $htmlData['element'];
        $this->attributes = new ThirdPartyHtmlAttributes($htmlData['attributes']);
    }

    /**
     * Returns an array representation of the data.
     *
     * @return array Associative array of data.
     */
    public function toArray(): array
    {
        return array(
            'element'    => $this->element,
            'attributes' => $this->attributes->toArray(),
        );
    }
}
