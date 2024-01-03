<?php
/**
 * Class GoogleChromeLabs\ThirdPartyCapital\Data\ThirdPartySrcValue
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Data;

use GoogleChromeLabs\ThirdPartyCapital\Contracts\Arrayable;
use GoogleChromeLabs\ThirdPartyCapital\Exception\InvalidThirdPartyDataException;

/**
 * Class representing a src value.
 */
class ThirdPartySrcValue implements Arrayable
{

    /**
     * URL for the src value.
     *
     * @var string
     */
    private $url;

    /**
     * Slug param for the src value, if needed.
     *
     * @var string
     */
    private $slugParam;

    /**
     * List of parameters for the src value, if needed.
     *
     * @var string[]
     */
    private $params;

    /**
     * Constructor.
     *
     * @param array $srcData Data for the src value.
     *
     * @throws InvalidThirdPartyDataException Thrown when the mandatory 'url' field is missing.
     */
    public function __construct(array $srcData)
    {
        if (! isset($srcData['url'])) {
            throw new InvalidThirdPartyDataException('Missing src url.');
        }

        $this->url = $srcData['url'];
        $this->slugParam = isset($srcData['slugParam']) ? (string) $srcData['slugParam'] : '';
        $this->params = isset($srcData['params']) ? array_map('strval', $srcData['params']) : array();
    }

    /**
     * Gets the URL for the src value.
     *
     * @return string URL for the src value.
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Gets the slug param for the src value, if needed.
     *
     * @return string Slug param for the src value, if needed.
     */
    public function getSlugParam(): string
    {
        return $this->slugParam;
    }

    /**
     * Gets the list of parameters for the src value, if needed.
     *
     * @return string[] List of parameters for the src value, if needed.
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Returns an array representation of the data.
     *
     * @return array Associative array of data.
     */
    public function toArray(): array
    {
        $data = array( 'url' => $this->url );
        if ($this->slugParam) {
            $data['slugParam'] = $this->slugParam;
        }
        if ($this->params) {
            $data['params'] = $this->params;
        }
        return $data;
    }
}
