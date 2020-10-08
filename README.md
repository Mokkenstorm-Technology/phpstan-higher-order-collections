# Higher Order Messaging support for Collections

This plugin adds [PHPStan](https://phpstan.org/) support to Higher Order Messaging on the Collection concept popularized by [Laravel](https://laravel.com/docs/8.x/collections#introduction).

This extension provides following features:

* `Illuminate\Support\Collection` knows the type(s) of its contents using [PHPStan Generics](https://phpstan.org/blog/generics-in-php-using-phpdocs), which ensures that methods called on the Proxy objects are actually valid, and have their return types correctly inferred.

## Installation

To use this extension, require it in [Composer](https://getcomposer.org/):

```
composer require sustainabil-it/phpstan-higher-order-collections
```

This plugin exposes a few configuration options:

```
parameters:
    higherOrderCollection:
        - collectionClass: Illuminate\Support\Collection
        - proxyClass: Illuminate\Support\HigherOrderCollectionProxy
        - typeTemplate: T 
        - proxyTemplate: S
        - proxyMethods:
            - map
            - filter
```

At time of writing you will need to configure these to work correctly with Laravel, this will be amended before the first tagged release. 


If you also install [phpstan/extension-installer](https://github.com/phpstan/extension-installer) then you're all set!

<details>
  <summary>Manual installation</summary>

If you don't want to use `phpstan/extension-installer`, include extension.neon in your project's PHPStan config:

```
includes:
    - vendor/sustainabil-it/phpstan-higher-order-collections/extension.neon
```

</details>
