<?php

namespace JBen87\DataBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class ProcessorCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('data.processor') as $id => $tags) {
            $container
                ->findDefinition('data.command.load_fixtures')
                ->addMethodCall('setProcessor', [$id, new Reference($id)])
            ;
        }
    }
}
