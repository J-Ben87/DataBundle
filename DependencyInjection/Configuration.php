<?php

namespace JBen87\DataBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var string
     */
    private $rootDir;

    /**
     * @param string $rootDir
     */
    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder;

        $rootNode = $treeBuilder->root('data');
        $rootNode
            ->children()
                ->scalarNode('culture')->isRequired()->end()
                ->scalarNode('fixtures_dir')->defaultValue(sprintf('%s/data/fixtures', $this->rootDir))->end()
                ->arrayNode('datasets')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->arrayNode('files')
                                ->isRequired()
                                ->requiresAtLeastOneElement()
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('processors')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('providers')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
