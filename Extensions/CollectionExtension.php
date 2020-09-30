<?php

namespace Plugin\Extensions;

use App\Infrastructure\Support\Collection;

use Plugin\Reflections\CollectionPropertyReflection;

use PHPStan\Reflection\{ClassReflection, PropertyReflection, PropertiesClassReflectionExtension};

class CollectionExtension implements PropertiesClassReflectionExtension
{
    /**
     * @var string[]
     */
    private array $properties = [
        'map',
        'filter'
    ];
    
    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        return $this->isValidProperty($propertyName) && ($classReflection->getName() === Collection::class || $classReflection->isSubclassOf(Collection::class));
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        assert($this->isValidProperty($propertyName));

        $reflection = new CollectionPropertyReflection($classReflection, $propertyName);
        
        return $reflection; 
    }

    private function isValidProperty(string $property) : bool
    {
        return in_array($property, $this->properties); 
    }
}
