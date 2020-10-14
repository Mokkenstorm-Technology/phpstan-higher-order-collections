<?php

namespace Tests\Custom;

use Tests\Common\Foo;
use Tests\Common\Bar;

(new Collection([ new Foo ]))->map->bar()->toArray();

(new Collection([ new Foo ]))->map->bar()->toArray();

(new Collection([ new Foo, new Bar ]))->map->bar()->toArray();
