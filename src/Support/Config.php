<?php

namespace SustainabilIT\PHPStanHOCPlugin\Support;

class Config implements ConfigInterface
{
    /**
     * @var class-string
     */
    private string $collectionClass;

    /**
     * @var class-string
     */
    private string $proxyClass;

    /**
     * @var string[]
     */
    private array $proxyMethods;

    private string $typeTemplate;

    private string $keyTemplate;
    
    private string $proxyTemplate;

    /**
     * @param class-string $collectionClass
     * @param class-string $proxyClass
     * @param string[] $proxyMethods
     */
    public function __construct(
        string $collectionClass,
        string $proxyClass,
        array $proxyMethods = ['map', 'filter'],
        string $keyTemplate = 'K',
        string $typeTemplate = 'T',
        string $proxyTemplate = 'S'
    ) {
        $this->collectionClass = $collectionClass;
        $this->proxyClass = $proxyClass;
        $this->proxyMethods = $proxyMethods;
        $this->keyTemplate = $keyTemplate;
        $this->typeTemplate = $typeTemplate;
        $this->proxyTemplate = $proxyTemplate;
    }

    /**
     * @return class-string
     */
    public function collectionClass(): string
    {
        return $this->collectionClass;
    }

    /**
     * @return class-string
     */
    public function proxyClass(): string
    {
        return $this->proxyClass;
    }

    /**
     * @return string[]
     */
    public function proxyMethods(): array
    {
        return $this->proxyMethods;
    }

    public function keyTemplate(): string
    {
        return $this->keyTemplate;
    }
    
    public function typeTemplate(): string
    {
        return $this->typeTemplate;
    }

    public function proxyTemplate(): string
    {
        return $this->proxyTemplate;
    }
}
