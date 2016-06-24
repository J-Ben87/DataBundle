<?php

namespace JBen87\DataBundle\Dataset;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
interface DatasetInterface
{
    /**
     * @return string[]
     */
    public function getFileNames();

    /**
     * @return string
     */
    public function getRepository();

    /**
     * @param string $repository
     *
     * @return DatasetInterface
     */
    public function setRepository($repository);
}
