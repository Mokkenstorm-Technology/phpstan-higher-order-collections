<?php

namespace Tests\Files;

use Tests\Classes\Collection;
use Tests\Classes\Foo;
use Tests\Classes\Bar;

(new Collection([ new Foo ]))->map->foo()->toArray();

(new Collection([ new Foo ]))->map->value->toArray();

Collection::from([ new Foo ])->map->foo()->toArray();

Collection::from([ new Foo ])->map->value->toArray();

Collection::from([ new Foo ])->map->value->mapInto(Bar::class)->map->value->toArray();

Collection::from([ new Foo ])->map->value->mapInto(Bar::class)->map->bar()->toArray();

Collection::from([ new Foo ])->filter(fn () : bool => true)->map->foo()->toArray();

Collection::from([ new Foo ])->filter->foo()->map->foo()->toArray();

Collection::from([ new Foo, new Bar ])->map->baz()->toArray();

Collection::from([ new Foo, new Bar ])->map->value->toArray();
