<?php

namespace Plugin\Extensions;

use App\Infrastructure\Support\HigherOrderCollectionProxy;

use PHPStan\Type\NeverType;
use PHPStan\Type\Type;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;

abstract class BaseHigherOrderCollectionExtension
{
    private ReflectionProvider $reflectionProvider;

    public function __construct(ReflectionProvider $reflectionProvider)
    {
        $this->reflectionProvider = $reflectionProvider;
    }
    
    /**
     * @template S
     * @param callable(ClassReflection): S $cb
     *
     * @return S[]
     */
    protected function mapClassReflections(ClassReflection $reflection, callable $cb) : array
    {
        return array_map(
            fn (string $class) => $cb($this->reflectionProvider->getClass($class)),
            $this->getTemplateType($reflection)->getReferencedClasses()
        );
    }

    protected function isCollectionProxy(ClassReflection $class) : bool
    {
        return $class->getName() === HigherOrderCollectionProxy::class ||
            $class->isSubclassOf(HigherOrderCollectionProxy::class);
    }
    
    protected function getTemplateType(ClassReflection $class) : Type
    {
        return $class->getActiveTemplateTypeMap()->getType('T') ?? new NeverType;
    }
}
