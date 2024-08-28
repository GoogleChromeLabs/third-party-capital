<?php
/**
 * Tests for GoogleChromeLabs\ThirdPartyCapital\Util\JsonDir
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Tests;

use GoogleChromeLabs\ThirdPartyCapital\TestUtils\TestCase;
use GoogleChromeLabs\ThirdPartyCapital\Util\JsonDir;

class JsonDirTest extends TestCase
{

    public function testGetFilePath()
    {
        $absPath = JsonDir::getFilePath('google-analytics.json');
        $this->assertStringEndsWith('/data/google-analytics.json', $absPath);
    }

    public function testGetFilePathWithLeadingSlash()
    {
        $absPath = JsonDir::getFilePath('/google-analytics.json');
        $this->assertStringEndsWith('/data/google-analytics.json', $absPath);
    }
}
