<?php

namespace JBen87\DataBundle;

use JBen87\DataBundle\DependencyInjection\Compiler\DatasetCompilerPass;
use JBen87\DataBundle\DependencyInjection\Compiler\ProcessorCompilerPass;
use JBen87\DataBundle\DependencyInjection\Compiler\ProviderCompilerPass;
use JBen87\DataBundle\DependencyInjection\DataExtension;
use Symfony\Component\Console\Application;
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

    /**
     * @inheritDoc
     */
    public function getContainerExtension()
    {
        return new DataExtension;
    }

    /**
     * @inheritDoc
     */
    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    /**
     * @inheritDoc
     */
    public function getPath()
    {
        return __DIR__;
    }

    /**
     * @inheritDoc
     */
    public function registerCommands(Application $application)
    {
    }
}
