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
# app/config/config.yml
data:
    culture: fr_FR # required - used to generate localized data with Faker
    fixtures_dir: "%kernel.root_dir%/data/fixtures" # default value - directory where datasets fixtures files are located
    datasets:
        fake:
            files:
                - "user.yml"
                - "address.yml"
        other:
            files:
                - "..."
```

## Basic usage

### Command

The bundle provides a command similar to [DoctrineFixturesBundle][1] to load your fixtures using [Alice][2] and [Faker][3].

```bash
Usage:
  bin/console data:fixtures:load <dataset> [options]

Arguments:
  dataset                    The dataset to load.

Options:
      --append               Append the data fixtures instead of deleting all data from the database first.
      --purge-with-truncate  Purge data by using a database-level TRUNCATE statement
```

### Dataset

To load your fixtures, you first need to create a **dataset**.

A dataset is made of two things:

- a directory containing `yml` fixtures files that will be loaded by [Alice][2]
- a `Dataset` service referencing the files to load (order matters)

Note: if you use configuration to define your datasets, the `Dataset` service will be automatically handled for you.

#### Defintion

All you need to do is to list the fixtures files to load in the configuration in the order you want them to be processed.

```yml
# app/config/config.yml
data:
    datasets:
        fake:
            files:
                - "user.yml"
                - "..."
```

#### Fixtures files

By default, the files containing the datasets fixtures are located in `app/data/fixtures` but [this can be configured](#configuration).

```yml
# app/data/fixtures/fake/user.yml
AppBundle\Entity\User:
    user_{1..10}:
        firstname: <firstName()>
        lastname: <lastName()>
        email: <email()>
        password: <password()>
```

That's it, you are ready to go!

## Advanced usage

### Providers & Processors

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

### Datasets

If you can't or don't want to use configuration to define your datasets, you can also create them manually.

Create a `Dataset` class somewhere in your project.
It must implement `JBen87\DataBundle\Dataset\DatasetInterface`.
Alternatively it can also extend the base class `JBen87\DataBundle\Dataset\Dataset`.

```php
// src/AppBundle/DataFixtures/Dataset/FakeDataset.php

use JBen87\DataBundle\Dataset\Dataset;

class FakeDataset extends Dataset
{
    /**
     * @inheritDoc
     */
    public function getFiles()
    {
        return [
            'user.yml',
        ];
    }
}
```

To be registered with the command, it must also be declared as a service with the tag `data.dataset`.
Optional tag attribute `alias` can be used to set the dataset name.

**Important:** if not provided, the dataset name is guessed from the service id (e.g. the name for the service `app.data_fixtures.dataset.fake` will be `fake`).

```yml
services:
    app.data_fixtures.dataset.fake:
        class: AppBundle\DataFixtures\Dataset\FakeDataset
        public: false
        tags:
            - { name: data.dataset }
```

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
