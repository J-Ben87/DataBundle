<?php

namespace JBen87\DataBundle\Dataset;

use Faker\Provider\Base as Provider;
use Nelmio\Alice\ProcessorInterface;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
interface DatasetInterface
{
    /**
     * @return string[]
     */
    public function getFiles();

    /**
     * @return ProcessorInterface[]
     */
    public function getProcessors();

    /**
     * @return Provider[]
     */
    public function getProviders();
}
