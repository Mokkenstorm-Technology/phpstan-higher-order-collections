<?php

namespace Plugin\Reflections;

use PHPStan\Analyser\OutOfClassScope;

use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\ParametersAcceptor;
use PHPStan\Reflection\ClassReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;
use PHPStan\Type\UnionType;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\NeverType;

use App\Infrastructure\Support\Collection;
use App\Infrastructure\Support\HigherOrderCollectionProxy;

class CollectionPropertyReflection implements PropertyReflection
{
    private ClassReflection $reflector;

    private string $method;

    /**
     * @param string $method
     */
    public function __construct(ClassReflection $reflector, string $method)
    {
        $this->reflector = $reflector;
        $this->method = $method;
    }

    public function getReadableType(): Type
    {
        assert(($inner = $this->reflector->getTemplateTypeMap()->getType('T')) !== null);
        
        $types = array_map(
            fn (ParametersAcceptor $acceptor) : Type => $acceptor->getReturnType(),
            $this->reflector->getMethod($this->method, new OutOfClassScope)->getVariants()
        );
        
        return new GenericObjectType(
            HigherOrderCollectionProxy::class,
            [
                $inner,
                count($types) > 1 ? new UnionType($types) : $types[0]
            ]
        );
    }

    public function getWritableType(): Type
    {
        return new NeverType(true);
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
