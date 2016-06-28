<?php

namespace JBen87\DataBundle\Dataset;

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
     * @param string[] $files
     */
    public function __construct(array $files = [])
    {
        $this->files = $files;
    }

    /**
     * @inheritDoc
     */
    public function getFiles()
    {
        return $this->files;
    }
}
