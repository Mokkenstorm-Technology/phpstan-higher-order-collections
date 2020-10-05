<?php

namespace Plugin\Extensions;

use App\Infrastructure\Support\Collection;

use Plugin\Reflections\CollectionPropertyReflection;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;

class CollectionExtension implements PropertiesClassReflectionExtension
{
    public function hasProperty(ClassReflection $class, string $property): bool
    {
        return $this->isCollection($class) && $this->isProxyable($property);
    }

    public function getProperty(ClassReflection $class, string $property): PropertyReflection
    {
        return new CollectionPropertyReflection($class, $property);
    }

    private function isProxyable(string $property) : bool
    {
        return in_array($property, [
            'filter',
            'map',
        ]);
    }

    private function isCollection(ClassReflection $class) : bool
    {
        return $class->getName() === Collection::class || $class->isSubclassOf(Collection::class);
    }
}
