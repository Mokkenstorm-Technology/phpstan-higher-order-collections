<?php

namespace Tests\Files;

use Tests\Classes\Collection;
use Tests\Classes\Foo;
use Tests\Classes\Bar;

(new Collection([ new Foo ]))->map->bar()->toArray();

(new Collection([ new Foo ]))->map->bar()->toArray();

Collection::from([ new Foo, new Bar ])->map->bar()->toArray();
