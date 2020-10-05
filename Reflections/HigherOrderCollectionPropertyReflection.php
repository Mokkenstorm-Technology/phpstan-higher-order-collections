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
    private ClassReflection $classReflection;
    
    private PropertyReflection $propertyReflection;
    
    public function __construct(
        PropertyReflection $propertyReflection,
        ClassReflection $classReflection
    ) {
        $this->propertyReflection = $propertyReflection;
        $this->classReflection = $classReflection;
    }

    public function getReadableType(): Type
    {
        assert(($type = $this->classReflection->withTypes([
            $this->propertyReflection->getReadableType()
        ])->getActiveTemplateTypeMap()->getType('S')) !== null);

        return $type;
    }

    public function getWritableType(): Type
    {
        return new NeverType(true);
    }

    public function getDeclaringClass(): ClassReflection
    {
        return $this->propertyReflection->getDeclaringClass();
    }

    public function isStatic(): bool
    {
        return $this->propertyReflection->isStatic();
    }

    public function isPrivate(): bool
    {
        return $this->propertyReflection->isPrivate();
    }

    public function isPublic(): bool
    {
        return $this->propertyReflection->isPublic();
    }

    public function canChangeTypeAfterAssignment(): bool
    {
        return $this->propertyReflection->canChangeTypeAfterAssignment();
    }

    public function isReadable(): bool
    {
        return $this->propertyReflection->isReadable();
    }

    public function isWritable(): bool
    {
        return false;
    }

    public function isDeprecated(): TrinaryLogic
    {
        return $this->propertyReflection->isDeprecated();
    }

    public function getdocComment(): ?string
    {
        return $this->propertyReflection->getdocComment();
    }

    public function getDeprecatedDescription(): ?string
    {
        return $this->propertyReflection->getDeprecatedDescription();
    }

    public function isInternal(): TrinaryLogic
    {
        return $this->propertyReflection->isInternal();
    }
}
