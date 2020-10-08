<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class CustomCollectionTest extends TestCase
{
    public function testValidCases(): void
    {
        $this->assertEmpty($this->analyse('ValidCalls'));
    }
    
    public function testInValidCases(): void
    {
        $expectedErrors = [
            9   => "Call to an undefined method Tests\Classes\HigherOrderCollectionProxy<Tests\Classes\Foo, Tests\Classes\Collection<S>>::bar().",
            11  => "Call to an undefined method Tests\Classes\HigherOrderCollectionProxy<Tests\Classes\Foo, Tests\Classes\Collection<S>>::bar().",
            13  => "Call to an undefined method Tests\Classes\HigherOrderCollectionProxy<Tests\Classes\Bar|Tests\Classes\Foo, Tests\Classes\Collection<S>>::bar()."
        ];

        $actualErrors = array_reduce(
            $this->analyse('InvalidCalls'),
            fn (array $acc, array $error): array => $acc + [ $error['line'] => $error['message'] ],
            []
        );

        foreach ($actualErrors as $line => $error) {
            $this->assertEquals($expectedErrors[$line], $error);
        }

        foreach (array_diff_assoc($expectedErrors, $actualErrors) as $line => $error) {
            $this->fail(sprintf("Expected failure %s: %s did not occur", $line, $error));
        }
    }
    
    /**
     * @return string[]
     */
    protected function analyse(string $file): array
    {
        $configPath = __DIR__. '/phpstan.neon';
        
        $command = escapeshellcmd(__DIR__.'/../vendor/bin/phpstan');

        $file = __DIR__ . '/Files/' . $file . '.php';

        exec(
            sprintf(
                '%s %s analyse --no-progress  --level=max --configuration %s  %s --error-format=%s --debug',
                escapeshellarg(PHP_BINARY),
                $command,
                escapeshellarg($configPath),
                escapeshellarg($file),
                'json'
            ),
            $jsonResult
        );

        return json_decode($jsonResult[1], true)['files'][$file]['messages'] ?? [];
    }
}
