<?php

namespace JBen87\DataBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class DatasetCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('data.dataset') as $id => $tags) {
            $attributes = reset($tags);
            $alias = isset($attributes['alias']) ? $attributes['alias'] : preg_replace('#.+\.([^.]+)$#', '$1', $id);

            $container
                ->findDefinition('data.command.load_fixtures')
                ->addMethodCall('setDataset', [$alias, new Reference($id)])
            ;
        }
    }
}
