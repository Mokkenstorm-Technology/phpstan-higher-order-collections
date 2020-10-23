<?php

namespace Tests\Laravel;

use Illuminate\Support\Collection;
use Illuminate\Support\HigherOrderCollectionProxy;

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

    protected string $space = 'Laravel';

    /**
     * @var array<int, string>
     */
    protected array $expectedErrors = [
        10  => "Call to an undefined method %proxy%<int, Tests\Common\Foo, %collection%<int, TReturn>>::bar().",
        12  => "Call to an undefined method %proxy%<int, Tests\Common\Foo, %collection%<int, TReturn>>::bar().",
        14  => "Call to an undefined method %proxy%<int, Tests\Common\Bar|Tests\Common\Foo, %collection%<int, TReturn>>::bar().",
        16  => "Call to an undefined method %proxy%<int, Tests\Common\Foo, %collection%<int, Tests\Common\Foo>>::bar().",
    ];
}
