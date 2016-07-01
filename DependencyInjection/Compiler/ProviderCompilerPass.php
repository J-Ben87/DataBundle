<?php

namespace JBen87\DataBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class ProviderCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('data.provider') as $id => $tags) {
            $container
                ->findDefinition('data.command.load_fixtures')
                ->addMethodCall('setProvider', [$id, new Reference($id)])
            ;
        }
    }
}
