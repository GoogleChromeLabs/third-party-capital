<?php
/**
 * Class GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyHtmlAttributes
 *
 * @package   Third Party Capital
 * @copyright 2023 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Data;

use GoogleChromeLabs\ThirdPartyCapital\Util\HtmlAttributes;

/**
 * Class representing a set of HTML Attributes, with extra support for source value definitions.
 */
class ThirdPartyHtmlAttributes extends HtmlAttributes
{

    /**
     * Returns the sanitized attribute value for the given attribute name and value.
     *
     * @param string $name  Attribute name.
     * @param mixed  $value Attribute value.
     * @return mixed Sanitized attribute value.
     */
    protected function sanitizeAttr(string $name, $value)
    {
        if (is_array($value)) {
            return new ThirdPartySrcValue($value);
        }

        return parent::sanitizeAttr($name, $value);
    }

    /**
     * Returns the attribute string for the given attribute name and value.
     *
     * @param string $name  Attribute name.
     * @param mixed  $value Attribute value.
     * @return string HTML attribute string (starts with a space), or empty string to skip.
     */
    protected function toAttrString(string $name, $value): string
    {
        if ($value instanceof ThirdPartySrcValue) {
            return ' ' . $name . '="' . $value->getUrl() . '"';
        }

        return parent::toAttrString($name, $value);
    }
}
