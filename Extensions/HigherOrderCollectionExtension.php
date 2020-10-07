<?php

namespace Plugin\Extensions;

use App\Infrastructure\Support\HigherOrderCollectionProxy;

use PHPStan\Analyser\OutOfClassScope;
use PHPStan\Type\NeverType;
use PHPStan\Type\Type;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\PropertiesClassReflectionExtension;

use Plugin\Reflections\HigherOrderCollectionMethodReflection;
use Plugin\Reflections\HigherOrderCollectionPropertyReflection;

class HigherOrderCollectionExtension implements MethodsClassReflectionExtension, PropertiesClassReflectionExtension
{
    private ReflectionProvider $reflectionProvider;

    public function __construct(ReflectionProvider $reflectionProvider)
    {
        $this->reflectionProvider = $reflectionProvider;
    }

    public function hasMethod(ClassReflection $class, string $method): bool
    {
        return $this->isCollectionProxy($class) && $this->getTemplateType($class)->hasMethod($method)->yes();
    }

    public function hasProperty(ClassReflection $class, string $property) : bool
    {
        return $this->isCollectionProxy($class) && $this->getTemplateType($class)->hasProperty($property)->yes();
    }

    public function getMethod(ClassReflection $class, string $method): MethodReflection
    {
        return new HigherOrderCollectionMethodReflection($class, $this->mapClassReflections(
            $class,
            fn (ClassReflection $reflection): MethodReflection => $reflection->getMethod($method, new OutOfClassScope)
        ));
    }

    public function getProperty(ClassReflection $class, string $property) : PropertyReflection
    {
        return new HigherOrderCollectionPropertyReflection($class, $this->mapClassReflections(
            $class,
            fn (ClassReflection $reflection): PropertyReflection => $reflection->getProperty($property, new OutOfClassScope)
        ));
    }

    /**
     * @template S
     * @param callable(ClassReflection): S $cb
     *
     * @return S[]
     */
    private function mapClassReflections(ClassReflection $reflection, callable $cb) : array
    {
        return array_map(
            fn (string $class) => $cb($this->reflectionProvider->getClass($class)),
            $this->getTemplateType($reflection)->getReferencedClasses()
        );
    }

    private function isCollectionProxy(ClassReflection $class) : bool
    {
        return $class->getName() === HigherOrderCollectionProxy::class ||
            $class->isSubclassOf(HigherOrderCollectionProxy::class);
    }
    
    private function getTemplateType(ClassReflection $class) : Type
    {
        return $class->getActiveTemplateTypeMap()->getType('T') ?? new NeverType;
    }
}
