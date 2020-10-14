<?php

namespace SustainabilIT\PHPStanHOCPlugin\Extensions;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;

use SustainabilIT\PHPStanHOCPlugin\Support\ConfigInterface;
use SustainabilIT\PHPStanHOCPlugin\Reflections\CollectionPropertyReflection;

class CollectionExtension implements PropertiesClassReflectionExtension
{
    private ConfigInterface $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function hasProperty(ClassReflection $class, string $property): bool
    {
        return $this->isCollection($class) && $this->isProxyable($property);
    }

    public function getProperty(ClassReflection $class, string $property): PropertyReflection
    {
        return new CollectionPropertyReflection($class, $property, $this->config);
    }

    private function isProxyable(string $property) : bool
    {
        return in_array($property, $this->config->proxyMethods());
    }

    private function isCollection(ClassReflection $class) : bool
    {
        $collection = $this->config->collectionClass();

        return $class->getName() === $collection || $class->isSubclassOf($collection);
    }
}
