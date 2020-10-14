<?php

namespace Tests\Files;

use Illuminate\Support\Collection;

use Tests\Common\Foo;
use Tests\Common\Bar;

(new Collection([ new Foo ]))->map->foo()->toArray();

(new Collection([ new Foo ]))->map->value->toArray();

Collection::make([ new Foo ])->map->foo()->toArray();

Collection::make([ new Foo ])->map->value->toArray();

Collection::make([ new Foo ])->map->value->mapInto(Bar::class)->map->value->toArray();

Collection::make([ new Foo ])->map->value->mapInto(Bar::class)->map->bar()->toArray();

Collection::make([ new Foo ])->filter(fn () : bool => true)->map->foo()->toArray();

Collection::make([ new Foo ])->filter->foo()->map->foo()->toArray();

Collection::make([ new Foo, new Bar ])->map->baz()->toArray();

Collection::make([ new Foo, new Bar ])->map->value->toArray();
