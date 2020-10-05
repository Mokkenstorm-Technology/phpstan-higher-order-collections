<?php

namespace Plugin\Reflections;

use App\Infrastructure\Support\Collection;

use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeTraverser;
use PHPStan\Reflection\ParametersAcceptor;
use PHPStan\Reflection\ClassReflection;

class CollectionParameterAcceptor implements ParametersAcceptor
{
    private ParametersAcceptor $acceptor;

    private ClassReflection $reflector;

    public function __construct(ParametersAcceptor $acceptor, ClassReflection $reflector)
    {
        $this->acceptor = $acceptor;
        $this->reflector = $reflector;
    }

    public function getReturnType(): Type
    {
        assert(($type = $this->reflector->withTypes([
            $this->acceptor->getReturnType()
        ])->getActiveTemplateTypeMap()->getType('S')) !== null);

        return $type;
    }
    
    public function getTemplateTypeMap(): TemplateTypeMap
    {
        return $this->acceptor->getTemplateTypeMap();
    }

    public function getResolvedTemplateTypeMap(): TemplateTypeMap
    {
        return $this->acceptor->getTemplateTypeMap();
    }
    
    /**
     * @return array<int, \PHPStan\Reflection\ParameterReflection>
     */
    public function getParameters(): array
    {
        return $this->acceptor->getParameters();
    }

    public function isVariadic(): bool
    {
        return $this->acceptor->isVariadic();
    }
}
