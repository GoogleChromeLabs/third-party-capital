<?php
/**
 * Interface GoogleChromeLabs\ThirdPartyCapital\Contracts\Arrayable
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Contracts;

/**
 * Interface for a class that can be transformed into an array.
 */
interface Arrayable
{

    /**
     * Returns an array representation of the data.
     *
     * @return array Associative array of data.
     */
    public function toArray(): array;
}
