<?php

namespace JBen87\DataBundle\Dataset;

use Faker\Provider\Base as Provider;
use Nelmio\Alice\ProcessorInterface;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class Dataset implements DatasetInterface
{
    /**
     * @var string[]
     */
    private $files = [];

    /**
     * @var ProcessorInterface[]
     */
    private $processors;

    /**
     * @var Provider[]
     */
    private $providers;

    /**
     * @param string[] $files
     * @param ProcessorInterface[] $processors
     * @param Provider[] $providers
     */
    public function __construct(array $files = [], array $processors = [], array $providers = [])
    {
        $this->files = $files;
        $this->processors = $processors;
        $this->providers = $providers;
    }

    /**
     * @inheritDoc
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @inheritDoc
     */
    public function getProcessors()
    {
        return $this->processors;
    }

    /**
     * @inheritDoc
     */
    public function getProviders()
    {
        return $this->providers;
    }
}
