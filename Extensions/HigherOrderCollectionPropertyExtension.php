<?php

namespace Plugin\Extensions;

use PHPStan\Analyser\OutOfClassScope;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;

use Plugin\Reflections\HigherOrderCollectionPropertyReflection;

class HigherOrderCollectionPropertyExtension extends BaseHigherOrderCollectionExtension implements PropertiesClassReflectionExtension
{
    public function hasProperty(ClassReflection $class, string $property) : bool
    {
        return $this->isCollectionProxy($class) && $this->getTemplateType($class)->hasProperty($property)->yes();
    }

    public function getProperty(ClassReflection $class, string $property) : PropertyReflection
    {
        return new HigherOrderCollectionPropertyReflection($class, $this->mapClassReflections(
            $class,
            fn (ClassReflection $reflection): PropertyReflection => $reflection->getProperty($property, new OutOfClassScope)
        ));
    }
}
