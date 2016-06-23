<?php

namespace JBen87\DataBundle;

use JBen87\DataBundle\DependencyInjection\Compiler\DatasetCompilerPass;
use JBen87\DataBundle\DependencyInjection\Compiler\ProcessorCompilerPass;
use JBen87\DataBundle\DependencyInjection\Compiler\ProviderCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class DataBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container
            ->addCompilerPass(new DatasetCompilerPass)
            ->addCompilerPass(new ProcessorCompilerPass)
            ->addCompilerPass(new ProviderCompilerPass)
        ;
    }
}
