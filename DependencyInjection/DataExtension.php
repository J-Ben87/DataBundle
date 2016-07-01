<?php

namespace JBen87\DataBundle\DependencyInjection;

use JBen87\DataBundle\Command\LoadFixturesCommand;
use JBen87\DataBundle\Dataset\Dataset;
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
        $this
            ->buildCommandDefintion($mergedConfig, $container)
            ->buildDatasetDefinitions($mergedConfig, $container)
        ;
    }

    /**
     * @inheritDoc
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new Configuration($container->getParameter('kernel.root_dir'));
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     *
     * @return DataExtension
     */
    private function buildCommandDefintion(array $config, ContainerBuilder $container)
    {
        $definition = new Definition(LoadFixturesCommand::class, [
            new Reference('doctrine.orm.entity_manager'),
            $config['fixtures_dir'],
            $config['culture']
        ]);

        $definition->addTag('console.command');

        $container->setDefinition('data.command.load_fixtures', $definition);

        return $this;
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     *
     * @return DataExtension
     */
    private function buildDatasetDefinitions(array $config, ContainerBuilder $container)
    {
        foreach ($config['datasets'] as $alias => $dataset) {
            $definition = new Definition(Dataset::class, [
                $dataset['files'],
                $this->buildAssociativeReferences($dataset['processors']),
                $this->buildAssociativeReferences($dataset['providers']),
            ]);

            $definition->addTag('data.dataset', ['alias' => $alias]);

            $container->setDefinition(sprintf('data.dataset.%s', $alias), $definition);
        }

        return $this;
    }

    /**
     * @param array $ids
     *
     * @return array
     */
    private function buildAssociativeReferences(array $ids)
    {
        $ids = array_map(function ($id) {
            return str_replace('@', '', $id);
        }, $ids);

        return array_combine($ids, array_map(function ($id) {
            return new Reference($id);
        }, $ids));
    }
}
