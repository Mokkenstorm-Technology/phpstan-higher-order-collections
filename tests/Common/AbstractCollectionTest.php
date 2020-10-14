<?php

namespace Tests\Common;

use PHPUnit\Framework\TestCase;

abstract class AbstractCollectionTest extends TestCase
{
    /**
     * @var class-string
     */
    protected string $collectionClass;

    /**
     * @var class-string
     */
    protected string $proxyClass;

    protected string $space;

    /**
     * @var array<int, string>
     */
    protected array $expectedErrors;

    /**
     * @test
     */
    public function testValidCases(): void
    {
        $errors = $this->getErrorsForFile('ValidCalls.php');

        $this->diffErrors($errors, []);

        $this->assertEmpty($errors);
    }

    /**
     * @test
     */
    public function testInValidCases(): void
    {
        $this->diffErrors($this->getErrorsForFile('InvalidCalls.php'), $this->getExpectedErrors());
    }

    protected function diffErrors(array $input, array $expected = []): void
    {
        foreach ($input as $line => $error) {
            $this->assertEquals($error, $expected[$line] ?? null);
        }

        foreach (array_diff_assoc($expected, $input) as $line => $error) {
            $this->throwError('Expected "' . $expected[$line] . '" got a different error on line %s: "%s"', $line, $error);
        }
    }

    protected function getErrorsForFile(string $file): array
    {
        return array_reduce(
            $this->analyse($this->file($file)),
            fn (array $acc, array $error): array => $acc + [ $error['line'] => $error['message'] ],
            []
        );
    }

    protected function getExpectedErrors(): array
    {
        return str_replace(
            [
                '%proxy%',
                '%collection%'
            ],
            [
                $this->proxyClass,
                $this->collectionClass
            ],
            $this->expectedErrors
        );
    }

    /**
     * @return string[]
     */
    protected function analyse(string $file): array
    {
        exec(
            sprintf(
                '%s %s analyse --no-progress  --level=max --configuration %s  %s --error-format=%s --debug',
                escapeshellarg(PHP_BINARY),
                escapeshellcmd($this->file('../../vendor/bin/phpstan')),
                escapeshellarg($this->file('config.neon')),
                escapeshellarg($file),
                'json'
            ),
            $jsonResult
        );

        return (array_pop(json_decode($jsonResult[1], true)['files']) ?? [])['messages'] ?? [];
    }

    protected function throwError(string $message, int $line, string $error): void
    {
        $this->fail(sprintf($message, $line, $error));
    }

    protected function file(string $file): string
    {
        return __DIR__ . '/../' . $this->space . '/' . $file;
    }
}
