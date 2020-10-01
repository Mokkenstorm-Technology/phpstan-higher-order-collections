<?php

namespace Plugin\Reflections;

use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Type;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\NeverType;

use App\Infrastructure\Support\HigherOrderCollectionProxy;

class CollectionPropertyReflection implements PropertyReflection
{
    private ClassReflection $reflector;

    /**
     * @var string
     */
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
        assert(($type = $this->reflector->getTemplateTypeMap()->getType('T')) !== null);
            
        return new GenericObjectType(
            HigherOrderCollectionProxy::class,
            [
                $type,
                new ConstantStringType($this->method)
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
