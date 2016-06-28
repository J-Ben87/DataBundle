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
    public function getFiles();
}
