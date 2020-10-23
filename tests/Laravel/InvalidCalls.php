<?php

namespace Tests\Laravel;

use Illuminate\Support\Collection;

use Tests\Common\Foo;
use Tests\Common\Bar;

(new Collection([ new Foo ]))->map->bar()->toArray();

(new Collection([ new Foo ]))->map->bar()->toArray();

(new Collection([ new Foo, new Bar ]))->map->bar()->toArray();

(new Collection([ new Foo ]))->each->bar();
