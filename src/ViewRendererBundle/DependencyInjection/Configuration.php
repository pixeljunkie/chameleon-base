<?php

namespace ChameleonSystem\ViewRendererBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('chameleon_system_view_renderer');
        $root->addDefaultsIfNotSet();

        $root
            ->children()
                ->scalarNode('css_dir')
                    ->defaultValue(PATH_USER_CMS_PUBLIC.'/outbox/static/less')
                    ->info('Directory path to where the less compiler caches work and the output files are stored.')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
