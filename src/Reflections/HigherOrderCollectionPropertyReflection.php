<?php

namespace PHPStan\HigherOrderCollections\Reflections;

use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use PHPStan\Type\ErrorType;

use PHPStan\HigherOrderCollections\Support\ConfigInterface;

class HigherOrderCollectionPropertyReflection implements PropertyReflection
{
    use AggregatesReflections;

    private ClassReflection $classReflection;

    private ConfigInterface $config;

    /**
     * @var PropertyReflection[]
     */
    private array $reflections;

    /**
     * @param PropertyReflection[] $reflections
     */
    public function __construct(
        ClassReflection $classReflection,
        ConfigInterface $config,
        array $reflections
    ) {
        $this->classReflection = $classReflection;
        $this->config = $config;
        $this->reflections = $reflections;
    }

    public function getReadableType(): Type
    {
        $types = $this->getPropertyTypes();

        return $this->classReflection
                    ->withTypes([
                        $this->classReflection->getTemplateTypeMap()->getType($this->config->keyTemplate()) ?? new ErrorType,
                        count($types) > 1 ? new UnionType($types) : $types[0]
                    ])
                    ->getTemplateTypeMap()
                    ->getType($this->config->proxyTemplate()) ?? new ErrorType;
    }

    /**
     * @return Type[]
     */
    private function getPropertyTypes(): array
    {
        return $this->mapReflections(
            fn (PropertyReflection $property) : Type => $property->getReadableType()
        );
    }

    public function getWritableType(): Type
    {
        return new ErrorType;
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
