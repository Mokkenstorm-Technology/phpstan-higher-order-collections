<?php

namespace Plugin\Reflections;

use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Type;
use PHPStan\Type\NeverType;
use PHPStan\Type\Generic\GenericObjectType;

use App\Infrastructure\Support\Collection;

class HigherOrderCollectionPropertyReflection implements PropertyReflection
{
    private PropertyReflection $reflector;
    
    public function __construct(PropertyReflection $reflector)
    {
        $this->reflector = $reflector;
    }

    public function getReadableType(): Type
    {
        return new GenericObjectType(
            Collection::class,
            [
                $this->reflector->getReadableType()
            ]
        );
    }

    public function getWritableType(): Type
    {
        return new NeverType(true);
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

    public function canChangeTypeAfterAssignment(): bool
    {
        return $this->reflector->canChangeTypeAfterAssignment();
    }

    public function isReadable(): bool
    {
        return $this->reflector->isReadable();
    }

    public function isWritable(): bool
    {
        return false;
    }

    public function isDeprecated(): TrinaryLogic
    {
        return $this->reflector->isDeprecated();
    }

    public function getdocComment(): ?string
    {
        return $this->reflector->getdocComment();
    }

    public function getDeprecatedDescription(): ?string
    {
        return $this->reflector->getDeprecatedDescription();
    }

    public function isInternal(): TrinaryLogic
    {
        return $this->reflector->isInternal();
    }
}
