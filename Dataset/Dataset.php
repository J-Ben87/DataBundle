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
    private $files;

    /**
     * @inheritDoc
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param string[] $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }
}
