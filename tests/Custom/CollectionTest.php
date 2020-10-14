<?php

namespace Tests\Custom;

use Tests\Common\AbstractCollectionTest;

class CollectionTest extends AbstractCollectionTest
{
    /**
     * @var class-string
     */
    protected string $collectionClass = Collection::class;

    /**
     * @var class-string
     */
    protected string $proxyClass = HigherOrderCollectionProxy::class;

    protected string $space = 'Custom';

    /**
     * @var array<int, string>
     */
    protected array $expectedErrors = [
        8   => "Call to an undefined method %proxy%<int, Tests\Common\Foo, %collection%<int, S>>::bar().",
        10  => "Call to an undefined method %proxy%<int, Tests\Common\Foo, %collection%<int, S>>::bar().",
        12  => "Call to an undefined method %proxy%<int, Tests\Common\Bar|Tests\Common\Foo, %collection%<int, S>>::bar()."
    ];
}
