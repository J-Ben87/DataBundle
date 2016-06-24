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

## Configuration

The bundle exposes the following configuration:

```yml
data:
    culture: fr_FR # required - used to generate localized data with Faker
    fixtures_dir: "%kernel.root_dir%/data/fixtures" # default value - directory where fixtures dataset are located
```

## Basic usage

The bundle provides a command similar to [DoctrineFixturesBundle][1] to load your fixtures using [Alice][2] and [Faker][3].

```bash
bin/console data:fixtures:load dataset [--append] [--purge-with-truncate]
```

To load your fixtures, you first need to create a **dataset**.

A dataset is made of two things:

- a directory containing `yml` files that will be loaded by [Alice][2]
- a `Dataset` class referencing the files to load (order matters)

By default, the directory is located in `app/data/fixtures/fake/` but [this can be configured](#configuration).

```yml
# app/data/fixtures/fake/user.yml
AppBundle\Entity\User:
    user_{1..10}:
        firstname: <firstName()>
        lastname: <lastName()>
        email: <email()>
        password: <password()>
```

The related `Dataset` class can be located anywhere.

It must implement `JBen87\DataBundle\Dataset\DatasetInterface`. The abstract class `JBen87\DataBundle\Dataset\Dataset` implements it and provides standard behavior.

```php
// src/AppBundle/DataFixtures/Dataset/FakeDataset.php

use JBen87\DataBundle\Dataset\Dataset;

class FakeDataset extends Dataset
{
    /**
     * @inheritDoc
     */
    public function getFileNames()
    {
        return [
            'user.yml',
        ];
    }
}
```

To be registered with the command, it must also be declared as a service with the tag `data.dataset`.

**Important:** the name of the dataset repository is guessed from the service id (e.g. the name for the service `app.data_fixtures.dataset.fake` will be `fake`).

```yml
services:
    app.data_fixtures.dataset.fake:
        class: AppBundle\DataFixtures\Dataset\FakeDataset
        public: false
        tags:
            - { name: data.dataset }
```

That's it, you are ready to go!

## Advanced usage

[Alice][2] comes with [Providers][4] and [Processors][5].

You can register yours with the command the same way you registered a `Dataset`: 

- providers must be tagged with `data.provider`
- processors must be tagged with `data.processor`

```yml
services:
    app.data_fixtures.provider.custom:
        class: AppBundle\DataFixtures\Provider\CustomProvider
        public: false
        tags:
            - { name: data.provider }
            
    app.data_fixtures.processor.user:
        class: AppBundle\DataFixtures\Processor\User
        public: false
        tags:
            - { name: data.processor }
```

They will automatically be available and used to write your fixtures and process them.

## Contributing

Pull requests are welcome.

Thanks to [everyone who has contributed](https://github.com/J-Ben87/DataBundle/graphs/contributors) already.

---

Released under the MIT License

[1]: https://github.com/doctrine/DoctrineFixturesBundle
[2]: https://github.com/nelmio/alice
[3]: https://github.com/fzaninotto/Faker
[4]: https://github.com/nelmio/alice/blob/2.x/doc/customizing-data-generation.md#custom-faker-data-providers
[5]: https://github.com/nelmio/alice/blob/2.x/doc/processors.md
