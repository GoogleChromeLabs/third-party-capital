<?php
/**
 * Class GoogleChromeLabs\ThirdPartyCapital\Util\HtmlAttributes
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Util;

use ArrayAccess;
use ArrayIterator;
use GoogleChromeLabs\ThirdPartyCapital\Contracts\Arrayable;
use GoogleChromeLabs\ThirdPartyCapital\Exception\NotFoundException;
use IteratorAggregate;
use Traversable;

/**
 * Class representing a set of HTML Attributes.
 */
class HtmlAttributes implements Arrayable, ArrayAccess, IteratorAggregate
{

    /**
     * Internal attributes storage.
     *
     * @var array
     */
    private $attr = [];

    /**
     * Constructor.
     *
     * @param array $attr Map of attribute names and their values.
     */
    public function __construct(array $attr)
    {
        foreach ($attr as $name => $value) {
            $this->attr[ $name ] = $this->sanitizeAttr($name, $value);
        }
    }

    /**
     * Checks if the given attribute is set.
     *
     * @since n.e.x.t
     *
     * @param string $name Attribute name.
     * @return bool True if the attribute is set, false otherwise.
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($name)
    {
        return isset($this->attr[ $name ]);
    }

    /**
     * Gets the value for the given attribute.
     *
     * @since n.e.x.t
     *
     * @param string $name Attribute name.
     * @return mixed Value for the given attribute.
     *
     * @throws NotFoundException Thrown if the attribute is not set.
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($name)
    {
        if (!isset($this->attr[ $name ])) {
            throw new NotFoundException(
                sprintf(
                    'Attribute with name %s not set.',
                    $name
                )
            );
        }

        return $this->attr[ $name ];
    }

    /**
     * Sets the given value for the given attribute.
     *
     * @since n.e.x.t
     *
     * @param string $name  Attribute name.
     * @param mixed  $value Attribute value.
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($name, $value)
    {
        // Not implemented, as the attributes are read-only.
    }

    /**
     * Unsets the value for the given attribute.
     *
     * @since n.e.x.t
     *
     * @param string $name Attribute name.
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($name)
    {
        // Not implemented, as the attributes are read-only.
    }

    /**
     * Returns an iterator for the attributes.
     *
     * @since n.e.x.t
     *
     * @return Traversable Attributes iterator.
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->attr);
    }

    /**
     * Returns an array representation of the data.
     *
     * @return array Associative array of data.
     */
    public function toArray(): array
    {
        return array_map(
            static function ($value) {
                if ($value instanceof Arrayable) {
                    return $value->toArray();
                }
                return $value;
            },
            $this->attr
        );
    }

    /**
     * Returns the HTML string of attributes to append to the HTML element tag name.
     *
     * @return string HTML attributes string.
     */
    public function __toString(): string
    {
        $output = '';
        foreach ($this->attr as $name => $value) {
            $output .= $this->toAttrString($name, $value);
        }
        return $output;
    }

    /**
     * Returns the sanitized attribute value for the given attribute name and value.
     *
     * @param string $name  Attribute name.
     * @param mixed  $value Attribute value.
     * @return mixed Sanitized attribute value.
     */
    protected function sanitizeAttr(string $name, $value)
    {
        if (is_bool($value)) {
            return $value;
        }
        return (string) $value;
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
        if (is_bool($value)) {
            return $value ? ' ' . $name : '';
        }

        return ' ' . $name . '="' . $value . '"';
    }
}
