<?php

namespace HOC\Extensions;

use PHPStan\Analyser\OutOfClassScope;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;

use HOC\Reflections\HigherOrderCollectionMethodReflection;

class HigherOrderCollectionMethodExtension extends BaseHigherOrderCollectionExtension implements MethodsClassReflectionExtension
{
    public function hasMethod(ClassReflection $class, string $method): bool
    {
        return $this->isCollectionProxy($class) && $this->getTemplateType($class)->hasMethod($method)->yes();
    }
    
    public function getMethod(ClassReflection $class, string $method): MethodReflection
    {
        return new HigherOrderCollectionMethodReflection(
            $class,
            $this->config,
            $this->mapClassReflections(
                $class,
                fn (ClassReflection $reflection): MethodReflection => $reflection->getMethod($method, new OutOfClassScope)
            )
        );
    }
}
