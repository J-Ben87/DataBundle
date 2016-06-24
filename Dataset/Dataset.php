<?php

namespace JBen87\DataBundle\Dataset;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
abstract class Dataset implements DatasetInterface
{
    /**
     * @var string
     */
    private $repository;

    /**
     * @inheritDoc
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @inheritDoc
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
    }
}
