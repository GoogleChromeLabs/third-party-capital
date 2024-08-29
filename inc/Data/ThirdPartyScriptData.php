<?php
/**
 * Class GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartyScriptData
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Data;

use GoogleChromeLabs\ThirdPartyCapital\Contracts\Arrayable;
use GoogleChromeLabs\ThirdPartyCapital\Exception\InvalidThirdPartyDataException;

/**
 * Class representing data for a third party script.
 */
class ThirdPartyScriptData implements Arrayable
{

    const STRATEGY_SERVER = 'server';
    const STRATEGY_CLIENT = 'client';
    const STRATEGY_IDLE   = 'idle';
    const STRATEGY_WORKER = 'worker';
    const LOCATION_HEAD   = 'head';
    const LOCATION_BODY   = 'body';
    const ACTION_APPEND   = 'append';
    const ACTION_PREPEND  = 'prepend';

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
     * List of parameters for the script, if needed.
     *
     * @var string[]
     */
    private $params;

    /**
     * Optional parameters for the script and their defaults, if needed.
     *
     * @var array<string, mixed>
     */
    private $optionalParams;

    /**
     * Constructor.
     *
     * @param array<string, mixed> $scriptData Script data, e.g. from a third party JSON file.
     *
     * @throws InvalidThirdPartyDataException Thrown when provided script data is invalid.
     */
    public function __construct(array $scriptData)
    {
        $this->validateData($scriptData);
        $this->setData($scriptData);
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
     * Gets the list of parameters for the script, if needed.
     *
     * @return string[] List of parameters for the script, if needed.
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Gets the optional parameters for the script with their defaults, if needed.
     *
     * @return array<string, mixed> Optional parameters for the script, if needed.
     */
    public function getOptionalParams(): array
    {
        return $this->optionalParams;
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
        if ($this->params) {
            $data['params'] = $this->params;
        }
        if ($this->optionalParams) {
            $data['optionalParams'] = $this->optionalParams;
        }

        return $data;
    }

    /**
     * Validates the given script data.
     *
     * @param array<string, mixed> $scriptData Script data, e.g. from a third party JSON file.
     *
     * @throws InvalidThirdPartyDataException Thrown when provided script data is invalid.
     */
    private function validateData(array $scriptData): void
    {
        $enumFields = ['strategy', 'location', 'action'];
        foreach ($enumFields as $enumField) {
            if (!isset($scriptData[ $enumField ])) {
                throw new InvalidThirdPartyDataException(
                    sprintf('Missing script %s.', $enumField)
                );
            }
            $methodName = 'isValid' . ucfirst($enumField);
            if (! call_user_func([$this, $methodName], $scriptData[ $enumField ])) {
                throw new InvalidThirdPartyDataException(
                    sprintf('Invalid script %s.', $enumField)
                );
            }
        }

        if (!isset($scriptData['url']) && !isset($scriptData['code'])) {
            throw new InvalidThirdPartyDataException(
                'Missing both script URL and script code, one of which must be provided.'
            );
        }
        if (isset($scriptData['url']) && isset($scriptData['code'])) {
            throw new InvalidThirdPartyDataException('Only one of script URL or script code must be provided.');
        }
    }

    /**
     * Checks whether the given strategy is valid.
     *
     * @param string $strategy Strategy to validate.
     * @return bool True if strategy is valid, false otherwise.
     */
    private function isValidStrategy(string $strategy): bool
    {
        return self::STRATEGY_SERVER === $strategy
            || self::STRATEGY_CLIENT === $strategy
            || self::STRATEGY_IDLE === $strategy
            || self::STRATEGY_WORKER === $strategy;
    }

    /**
     * Checks whether the given location is valid.
     *
     * @param string $location Location to validate.
     * @return bool True if location is valid, false otherwise.
     */
    private function isValidLocation(string $location): bool
    {
        return self::LOCATION_HEAD === $location || self::LOCATION_BODY === $location;
    }

    /**
     * Checks whether the given action is valid.
     *
     * @param string $action Action to validate.
     * @return bool True if action is valid, false otherwise.
     */
    private function isValidAction(string $action): bool
    {
        return self::ACTION_APPEND === $action || self::ACTION_PREPEND === $action;
    }

    /**
     * Sets the given script data.
     *
     * @param array<string, mixed> $scriptData Script data, e.g. from a third party JSON file.
     */
    private function setData(array $scriptData): void
    {
        $strFields = ['strategy', 'location', 'action', 'url', 'code', 'key'];
        foreach ($strFields as $field) {
            $this->$field = isset($scriptData[ $field ]) ? (string) $scriptData[ $field ] : '';
        }

        $this->params         = isset($scriptData['params']) ? array_map('strval', $scriptData['params']) : [];
        $this->optionalParams = isset($scriptData['optionalParams']) ? (array) $scriptData['optionalParams'] : [];
    }
}
