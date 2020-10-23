<?php

namespace PHPStan\HigherOrderCollections\Reflections;

use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ParametersAcceptor;
use PHPStan\ShouldNotHappenException;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

use PHPStan\HigherOrderCollections\Support\ConfigInterface;

class HigherOrderCollectionMethodReflection implements MethodReflection
{
    use AggregatesReflections;

    private ClassReflection $classReflection;

    private ConfigInterface $config;
    
    /**
     * @var MethodReflection[]
     */
    private array $reflections;

    /**
     * @param MethodReflection[] $reflections
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

    /**
     * @return \PHPStan\Reflection\ParametersAcceptor[]
     */
    public function getVariants(): array
    {
        $decorator = fn (ParametersAcceptor $acceptor) : ParametersAcceptor =>
            new HigherOrderCollectionParameterAcceptor(
                $acceptor,
                $this->classReflection,
                $this->config
            );

        $results = array_merge(...$this->mapReflections(
            fn (MethodReflection $reflection) : array =>
                array_map($decorator, $reflection->getVariants())
        ));

        if (!$results) {
            throw new ShouldNotHappenException("Attempted Higher Order Messaging for unsupported method");
        }

        return $results;
    }

    public function getDeclaringClass(): ClassReflection
    {
        return $this->classReflection;
    }

    public function isStatic(): bool
    {
        return $this->checkAnyReflectionsPass(fn (MethodReflection $method): bool => $method->isStatic());
    }

    public function isPrivate(): bool
    {
        return $this->checkAnyReflectionsPass(fn (MethodReflection $method): bool => $method->isPrivate());
    }

    public function isPublic(): bool
    {
        return $this->checkAllReflectionsPass(fn (MethodReflection $method): bool => $method->isPublic());
    }

    public function getName(): string
    {
        return $this->reflections ? $this->reflections[0]->getName() : 'No Reflections';
    }

    public function getPrototype(): self
    {
        return $this;
    }

    public function isDeprecated(): TrinaryLogic
    {
        return $this->trinaryForReflection(fn (MethodReflection $method): bool => $method->isDeprecated()->no());
    }

    public function getDeprecatedDescription(): ?string
    {
        $messages = $this->mapReflections(fn (MethodReflection $method): ? string => $method->getDeprecatedDescription());
        
        return implode("\n", array_filter($messages)) ?: null;
    }

    public function isFinal(): TrinaryLogic
    {
        return $this->trinaryForReflection(fn (MethodReflection $method): bool => $method->isFinal()->no());
    }

    public function isInternal(): TrinaryLogic
    {
        return $this->trinaryForReflection(fn (MethodReflection $method): bool => $method->isInternal()->no());
    }

    public function getThrowType(): ? Type
    {
        $errors = array_filter($this->mapReflections(fn (MethodReflection $method): ? Type => $method->getThrowType()));

        return $errors ? (count($errors) > 1 ? new UnionType($errors) : $errors[0]) : null;
    }

    public function hasSideEffects(): TrinaryLogic
    {
        return $this->trinaryForReflection(fn (MethodReflection $method): bool => $method->hasSideEffects()->no());
    }

    public function getDocComment(): ?string
    {
        $messages = $this->mapReflections(fn (MethodReflection $method): ? string => $method->getDocComment());
        
        return implode("\n", array_filter($messages)) ?: null;
    }
}
