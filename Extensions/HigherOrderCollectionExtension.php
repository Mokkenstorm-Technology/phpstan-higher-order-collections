<?php

namespace Plugin\Extensions;

use App\Infrastructure\Support\Collection;

use PHPStan\Analyser\Scope;
use PHPStan\Analyser\OutOfClassScope;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\Generic\GenericObjectType;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\PropertiesClassReflectionExtension;

use PhpParser\Node\Expr\MethodCall;

use Plugin\Reflections\HigherOrderCollectionMethodReflection;
use Plugin\Reflections\HigherOrderCollectionPropertyReflection;

class HigherOrderCollectionExtension implements MethodsClassReflectionExtension, PropertiesClassReflectionExtension
{
    private ReflectionProvider $reflectionProvider;

    public function __construct(ReflectionProvider $reflectionProvider)
    {
        $this->reflectionProvider = $reflectionProvider;
    }

    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        if (($type = $this->getTemplateType($classReflection)) === null) {
            return false;
        }
        
        return $this->reflectionProvider->getClass($type->getClassName())->hasMethod($methodName);
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        assert(($type = $this->getTemplateType($classReflection)) !== null);
        assert($this->reflectionProvider->hasClass($type->getClassName()));

        return new HigherOrderCollectionMethodReflection(
            $this->reflectionProvider->getClass($type->getClassName())->getMethod(
                $methodName,
                new OutOfClassScope
            )
        );
    }

    public function hasProperty(ClassReflection $classReflection, string $propertyName) : bool
    {
        if (($type = $this->getTemplateType($classReflection)) === null) {
            return false;
        }
        
        return $this->reflectionProvider->getClass($type->getClassName())->hasProperty($propertyName);
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName) : PropertyReflection
    {
        assert(($type = $this->getTemplateType($classReflection)) !== null);

        return new HigherOrderCollectionPropertyReflection(
            $this->reflectionProvider->getClass($type->getClassName())->getProperty(
                $propertyName,
                new OutOfClassScope
            )
        );
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        $type = $this->getTemplateType($methodReflection->getDeclaringClass());

        return ($type instanceof ObjectType) && $type->hasMethod($methodReflection->getName())->yes();
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ) : Type {
        $type = $this->getTemplateType($methodReflection->getDeclaringClass());

        assert($type instanceof ObjectType);

        $classReflector = $type->getClassReflection();

        assert($classReflector !== null);

        return new GenericObjectType(Collection::class, [$type]);
    }

    private function getTemplateType(ClassReflection $classReflection) : ? ObjectType
    {
        if (($type = $classReflection->getActiveTemplateTypeMap()->getType('T')) === null) {
            return null;
        }

        if (!$type instanceof ObjectType) {
            return null;
        }

        if (!$this->reflectionProvider->hasClass($type->getClassName())) {
            return null;
        }

        return $type;
    }
}
