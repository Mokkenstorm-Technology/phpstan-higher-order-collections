<?php

namespace Plugin\Reflections;

use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ParametersAcceptor;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Type;

class HigherOrderCollectionMethodReflection implements MethodReflection
{
    private ClassReflection $classReflection;
    
    private MethodReflection $methodReflection;
    
    public function __construct(
        MethodReflection $methodReflection,
        ClassReflection $classReflection
    ) {
        $this->methodReflection = $methodReflection;
        $this->classReflection = $classReflection;
    }

    /**
     * @return \PHPStan\Reflection\ParametersAcceptor[]
     */
    public function getVariants(): array
    {
        return array_map(
            fn (ParametersAcceptor $acceptor) : ParametersAcceptor =>
                new CollectionParameterAcceptor(
                    $acceptor,
                    $this->classReflection
                ),
            $this->methodReflection->getVariants()
        );
    }
    
    public function getDeclaringClass(): ClassReflection
    {
        return $this->methodReflection->getDeclaringClass();
    }

    public function isStatic(): bool
    {
        return $this->methodReflection->isStatic();
    }

    public function isPrivate(): bool
    {
        return $this->methodReflection->isPrivate();
    }

    public function isPublic(): bool
    {
        return $this->methodReflection->isPublic();
    }

    public function getName(): string
    {
        return $this->methodReflection->getName();
    }

    public function getPrototype(): self
    {
        return $this;
    }

    public function isDeprecated(): TrinaryLogic
    {
        return $this->methodReflection->isDeprecated();
    }

    public function getDeprecatedDescription(): ?string
    {
        return $this->methodReflection->getDeprecatedDescription();
    }

    public function isFinal(): TrinaryLogic
    {
        return $this->methodReflection->isFinal();
    }

    public function isInternal(): TrinaryLogic
    {
        return $this->methodReflection->isInternal();
    }

    public function getThrowType(): ?Type
    {
        return $this->methodReflection->getThrowType();
    }

    public function hasSideEffects(): TrinaryLogic
    {
        return $this->methodReflection->hasSideEffects();
    }

    public function getDocComment(): ?string
    {
        return $this->methodReflection->getDocComment();
    }
}
