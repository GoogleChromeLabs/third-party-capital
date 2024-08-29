<?php
/**
 * Test base class.
 *
 * @package   GoogleChromeLabs/ThirdPartyCapital
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace GoogleChromeLabs\ThirdPartyCapital\TestUtils;

use Exception;
use GoogleChromeLabs\ThirdPartyCapital\Contracts\Arrayable;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{

    protected function runGetterTestCase(string $className, string $getMethod, array $args, $expected)
    {
        if (is_subclass_of($expected, Exception::class)) {
            $this->expectException($expected);

            $instance = new $className($args);
            call_user_func([$instance, $getMethod]);
            return;
        }

        $instance = new $className($args);
        $result   = call_user_func([$instance, $getMethod]);
        if ($result instanceof Arrayable) {
            $result = $result->toArray();
        } elseif (is_array($result)) {
            $result = array_map(
                static function ($entry) {
                    if ($entry instanceof Arrayable) {
                        return $entry->toArray();
                    }
                    return $entry;
                },
                $result
            );
        }
        $this->assertSame($expected, $result);
    }

    protected function gettersToTestCases(array $getters, string $exceptionClass = null): array
    {
        if (!$exceptionClass) {
            $exceptionClass = Exception::class;
        }

        $requiredFields = [];
        foreach ($getters as $getter) {
            if (!isset($getter['required']) || ! $getter['required']) {
                continue;
            }

            if (!isset($getter['value'])) {
                $type  = isset($getter['default']) ? gettype($getter['default']) : 'string';
                $value = $this->createValueOfType($type);
            } else {
                $value = $getter['value'];
            }
            $requiredFields[$getter['field']] = $value;
        }

        $testCases = [];
        foreach ($getters as $getter) {
            if (!isset($getter['value'])) {
                $type  = isset($getter['default']) ? gettype($getter['default']) : 'string';
                $value = $this->createValueOfType($type);
            } else {
                $value = $getter['value'];
            }
            $args                   = $requiredFields;
            $args[$getter['field']] = $value;

            $testCases["{$getter['getter']} with value"] = [
                $getter['getter'],
                $args,
                $value,
            ];

            if ((isset($getter['required']) && $getter['required']) || isset($getter['default'])) {
                unset($args[$getter['field']]);

                $testCases["{$getter['getter']} without value"] = [
                    $getter['getter'],
                    $args,
                    isset($getter['required']) && $getter['required'] ?
                        $exceptionClass :
                        $getter['default']
                ];
            }
        }
        return $testCases;
    }

    private function createValueOfType(string $type)
    {
        switch ($type) {
            case 'bool':
            case 'boolean':
                return true;
            case 'double':
            case 'float':
                return 5.9;
            case 'int':
            case 'integer':
                return 23;
            case 'array':
                return ['id'];
        }

        // Default 'string'.
        return 'something';
    }
}
