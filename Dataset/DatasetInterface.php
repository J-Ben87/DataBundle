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
    public function getName();

    /**
     * @param string $name
     *
     * @return DatasetInterface
     */
    public function setName($name);
}
