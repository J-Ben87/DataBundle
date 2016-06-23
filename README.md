DataBundle
==========

## Installation

Install with composer:

```bash
composer require j-ben87/data-bundle
```

Register the bundle in your `app/AppKernel.php`:

```php
public function registerBundles()
{
    $bundles = [
        // ...
        new JBen87\DataBundle\DataBundle(),
    ];
    
    // ...
}
```
