<?php
/**
 * Tests for GoogleChromeLabs\ThirdPartyCapital\Util\ThirdPartiesDir
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\Tests;

use GoogleChromeLabs\ThirdPartyCapital\TestUtils\TestCase;
use GoogleChromeLabs\ThirdPartyCapital\Util\ThirdPartiesDir;

class ThirdPartiesDirTest extends TestCase
{

    public function testGetFilePath()
    {
        $absPath = ThirdPartiesDir::getFilePath('google-analytics/data.json');
        $this->assertStringEndsWith('/src/third-parties/google-analytics/data.json', $absPath);
    }

    public function testGetFilePathWithLeadingSlash()
    {
        $absPath = ThirdPartiesDir::getFilePath('/google-analytics/data.json');
        $this->assertStringEndsWith('/src/third-parties/google-analytics/data.json', $absPath);
    }
}
