<?php

namespace PHPStan\HigherOrderCollections\Reflections;

use PHPStan\Analyser\OutOfClassScope;

use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\ParametersAcceptor;
use PHPStan\Reflection\ClassReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\ErrorType;

use PHPStan\HigherOrderCollections\Support\ConfigInterface;

class CollectionPropertyReflection implements PropertyReflection
{
    private ClassReflection $reflector;

    private string $method;
    
    private ConfigInterface $config;

    /**
     * @param string $method
     */
    public function __construct(ClassReflection $reflector, string $method, ConfigInterface $config)
    {
        $this->reflector = $reflector;
        $this->method = $method;
        $this->config = $config;
    }

    public function getReadableType(): Type
    {
        [ $keyType, $elementType ] = $this->getTemplateTypes([$this->config->keyTemplate(), $this->config->typeTemplate()]);

        $returnType = count($types = $this->mapAcceptors()) > 1 ? new UnionType($types) : $types[0];

        return new GenericObjectType($this->config->proxyClass(), [$keyType, $elementType, $returnType]);
    }

    /**
     * @param string[] $keys
     * @return Type[]
     */
    private function getTemplateTypes(array $keys)
    {
        return array_map(
            fn (string $key) : Type => $this->reflector->getTemplateTypeMap()->getType($key) ?? new ErrorType,
            $keys
        );
    }

    /**
     * @return Type[]
     */
    private function mapAcceptors() : array
    {
        return array_map(
            fn (ParametersAcceptor $acceptor) : Type => $acceptor->getReturnType(),
            $this->reflector->getMethod($this->method, new OutOfClassScope)->getVariants()
        );
    }

    public function getWritableType(): Type
    {
        return new ErrorType;
    }

    public function getDeclaringClass(): ClassReflection
    {
        return $this->reflector;
    }

    public function isStatic(): bool
    {
        return false;
    }

    public function isPrivate(): bool
    {
        return false;
    }

    public function isPublic(): bool
    {
        return true;
    }

    public function canChangeTypeAfterAssignment(): bool
    {
        return true;
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function isWritable(): bool
    {
        return false;
    }

    public function isDeprecated(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function getdocComment(): ?string
    {
        return null;
    }

    public function getDeprecatedDescription(): ?string
    {
        return null;
    }

    public function isInternal(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }
}
