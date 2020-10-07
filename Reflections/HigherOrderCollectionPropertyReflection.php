<?php

namespace Plugin\Reflections;

use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use PHPStan\Type\NeverType;
use PHPStan\Type\Generic\GenericObjectType;

use App\Infrastructure\Support\Collection;

class HigherOrderCollectionPropertyReflection implements PropertyReflection
{
    use AggregatesReflections;

    private ClassReflection $classReflection;

    /**
     * @var PropertyReflection[]
     */
    private array $reflections;

    /**
     * @param PropertyReflection[] $reflections
     */
    public function __construct(ClassReflection $classReflection, array $reflections)
    {
        $this->classReflection = $classReflection;
        $this->reflections = $reflections;
    }

    public function getReadableType(): Type
    {
        $propertyTypes = $this->mapReflections(fn (PropertyReflection $property) : Type => $property->getReadableType());

        assert(($type = $this->classReflection->withTypes([
        
            count($propertyTypes) > 1 ? new UnionType($propertyTypes) : $propertyTypes[0]
        
        ])->getActiveTemplateTypeMap()->getType('S')) !== null);

        return $type;
    }

    public function getWritableType(): Type
    {
        return new NeverType(true);
    }

    public function getDeclaringClass(): ClassReflection
    {
        return $this->classReflection;
    }

    public function isStatic(): bool
    {
        return $this->checkAnyReflectionsPass(fn (PropertyReflection $property): bool => $property->isStatic());
    }

    public function isPrivate(): bool
    {
        return $this->checkAnyReflectionsPass(fn (PropertyReflection $property): bool => $property->isPrivate());
    }

    public function isPublic(): bool
    {
        return $this->checkAllReflectionsPass(fn (PropertyReflection $property): bool => $property->isPublic());
    }

    public function canChangeTypeAfterAssignment(): bool
    {
        return false;
    }

    public function isReadable(): bool
    {
        return $this->checkAllReflectionsPass(fn (PropertyReflection $property): bool => $property->isReadable());
    }

    public function isWritable(): bool
    {
        return false;
    }

    public function isDeprecated(): TrinaryLogic
    {
        return $this->trinaryForReflection(fn (PropertyReflection $property): bool => $property->isDeprecated()->no());
    }

    public function getdocComment(): ?string
    {
        $messages = $this->mapReflections(fn (PropertyReflection $property): ? string => $property->getDocComment());
        
        return implode("\n", array_filter($messages)) ?: null;
    }

    public function getDeprecatedDescription(): ?string
    {
        $messages = $this->mapReflections(fn (PropertyReflection $property): ? string => $property->getDeprecatedDescription());
        
        return implode("\n", array_filter($messages)) ?: null;
    }

    public function isInternal(): TrinaryLogic
    {
        return $this->trinaryForReflection(fn (PropertyReflection $property): bool => $property->isInternal()->no());
    }
}
