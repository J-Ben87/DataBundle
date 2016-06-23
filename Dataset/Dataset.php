<?php

namespace JBen87\DataBundle;

use JBen87\DataBundle\Dataset\DatasetInterface;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
abstract class Dataset implements DatasetInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
