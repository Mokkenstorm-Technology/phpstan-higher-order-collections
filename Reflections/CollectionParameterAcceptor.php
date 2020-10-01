<?php

namespace Plugin\Reflections;

use App\Infrastructure\Support\Collection;

use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\Type;
use PHPStan\Reflection\ParametersAcceptor;

class CollectionParameterAcceptor implements ParametersAcceptor
{
    private ParametersAcceptor $acceptor;

    public function __construct(ParametersAcceptor $acceptor)
    {
        $this->acceptor = $acceptor;
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

    public function getReturnType(): Type
    {
        return new GenericObjectType(Collection::class, [$this->acceptor->getReturnType()]);
    }
}
