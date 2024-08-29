<?php
/**
 * Class GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyScriptOutput
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Data;

use GoogleChromeLabs\ThirdPartyCapital\Contracts\Arrayable;

/**
 * Class representing the output data for a third party script.
 */
class ThirdPartyScriptOutput implements Arrayable
{

    /**
     * Strategy for including the script.
     *
     * @var string
     */
    private $strategy;

    /**
     * Location where to include the script.
     *
     * @var string
     */
    private $location;

    /**
     * Action how to include the script.
     *
     * @var string
     */
    private $action;

    /**
     * Script URL, only relevant if an external script.
     *
     * @var string
     */
    private $url;

    /**
     * Script code, only relevant if an inline script.
     *
     * @var string
     */
    private $code;

    /**
     * Script key, if provided.
     *
     * @var string
     */
    private $key;

    /**
     * Constructor.
     *
     * @param array<string, mixed> $scriptData Script output data.
     */
    public function __construct(array $scriptData)
    {
        $strFields = ['strategy', 'location', 'action', 'url', 'code', 'key'];
        foreach ($strFields as $field) {
            $this->$field = isset($scriptData[ $field ]) ? (string) $scriptData[ $field ] : '';
        }
    }

    /**
     * Gets the strategy for including the script.
     *
     * @return string Strategy for including the script.
     */
    public function getStrategy(): string
    {
        return $this->strategy;
    }

    /**
     * Gets the location where to include the script.
     *
     * @return string Location where to include the script.
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * Gets the action how to include the script.
     *
     * @return string Action how to include the script.
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Gets the script URL, if an external script.
     *
     * @return string Script URL, if an external script.
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Gets the script code, if an inline script.
     *
     * @return string Script code, if an inline script.
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Gets the script key, if provided.
     *
     * @return string Script key, if provided.
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Determines whether the script is an external script.
     *
     * @return bool True if an external script, false if an inline script.
     */
    public function isExternal(): bool
    {
        return '' !== $this->url;
    }

    /**
     * Returns an array representation of the data.
     *
     * @return array<string, mixed> Associative array of data.
     */
    public function toArray(): array
    {
        $data = [
            'strategy' => $this->strategy,
            'location' => $this->location,
            'action'   => $this->action,
        ];
        if ($this->url) {
            $data['url'] = $this->url;
        } else {
            $data['code'] = $this->code;
        }
        if ($this->key) {
            $data['key'] = $this->key;
        }

        return $data;
    }
}
