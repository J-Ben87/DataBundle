<?php

namespace JBen87\DataBundle\DependencyInjection;

use JBen87\DataBundle\Command\LoadFixturesCommand;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class DataExtension extends ConfigurableExtension
{
    /**
     * @inheritDoc
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $container->setParameter('data.culture', $mergedConfig['culture']);
        $container->setParameter('data.fixtures_dir', $mergedConfig['fixtures_dir']);

        // create service definition for load_fixtures command
        $definition = new Definition(LoadFixturesCommand::class, [
            new Reference('doctrine.orm.entity_manager'),
            $container->getParameter('data.fixtures_dir'),
            $container->getParameter('data.culture'),
        ]);

        $definition->addTag('console.command');

        $container->setDefinition('data.command.load_fixtures', $definition);
    }

    /**
     * @inheritDoc
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new Configuration($container->getParameter('kernel.root_dir'));
    }
}
