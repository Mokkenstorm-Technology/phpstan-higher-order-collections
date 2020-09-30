<?php

namespace Plugin\Reflections;

use PHPStan\Reflection\{MethodReflection, ClassReflection, ParametersAcceptor};
use PHPStan\{TrinaryLogic, Type\Type};

class HigherOrderCollectionMethodReflection implements MethodReflection
{
    private MethodReflection $reflector; 
    
    public function __construct(MethodReflection $reflector)
    {
        $this->reflector = $reflector; 
    }
    
    public function getDeclaringClass(): ClassReflection
    {
        return $this->reflector->getDeclaringClass(); 
    }

    public function isStatic(): bool
    {
        return $this->reflector->isStatic(); 
    }

    public function isPrivate(): bool
    {
        return $this->reflector->isPrivate(); 
    }

    public function isPublic(): bool
    {
        return $this->reflector->isPublic(); 
    }

    public function getName(): string
    {
        return $this->reflector->getName();
    }

    public function getPrototype(): self
    {
        return $this; 
    }

	/**
	 * @return \PHPStan\Reflection\ParametersAcceptor[]
	 */
    public function getVariants(): array
    {
        return array_map(
            fn (ParametersAcceptor $acceptor) : ParametersAcceptor =>
                new CollectionParameterAcceptor($acceptor),
            $this->reflector->getVariants()
        );
    }

    public function isDeprecated(): TrinaryLogic
    {
        return $this->reflector->isDeprecated();
    }

    public function getDeprecatedDescription(): ?string
    {
        return $this->reflector->getDeprecatedDescription(); 
    }

    public function isFinal(): TrinaryLogic
    {
        return $this->reflector->isFinal(); 
    }

    public function isInternal(): TrinaryLogic
    {
        return $this->reflector->isInternal(); 
    }

    public function getThrowType(): ?Type
    {
        return $this->reflector->getThrowType(); 
    }

    public function hasSideEffects(): TrinaryLogic
    {
        return $this->reflector->hasSideEffects();
    }

    public function getDocComment(): ?string
    {
        return $this->reflector->getDocComment(); 
    }
}
