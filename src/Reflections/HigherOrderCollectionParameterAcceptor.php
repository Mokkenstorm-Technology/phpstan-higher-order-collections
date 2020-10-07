<?php

namespace HOC\Reflections;

use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\Type;
use PHPStan\Type\NeverType;
use PHPStan\Reflection\ParametersAcceptor;
use PHPStan\Reflection\ClassReflection;

use HOC\Support\ConfigInterface;

class HigherOrderCollectionParameterAcceptor implements ParametersAcceptor
{
    private ParametersAcceptor $acceptor;

    private ClassReflection $reflector;
    
    private ConfigInterface $config;

    public function __construct(ParametersAcceptor $acceptor, ClassReflection $reflector, ConfigInterface $config)
    {
        $this->acceptor = $acceptor;
        $this->reflector = $reflector;
        $this->config = $config;
    }

    public function getReturnType(): Type
    {
        return $this->reflector
                    ->withTypes([ $this->acceptor->getReturnType() ])
                    ->getActiveTemplateTypeMap()
                    ->getType($this->config->proxyTemplate()) ?? new NeverType;
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
