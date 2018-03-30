<?php

namespace Apigen\GeneratorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('apigen_generator');

        $rootNode
	        ->children()
	            ->arrayNode('apis')
	                ->useAttributeAsKey('api_name')
	                ->prototype('array')
	                    ->children()
					        ->arrayNode('resources')
					            ->children()
					                ->arrayNode('tags')
	                                    ->prototype('scalar')
	                                    ->end()
					                ->end()
					            ->end()
				            ->end()
							->scalarNode('file')
	                        ->end()
	                        ->arrayNode('routing')
	                            ->children()
	                                ->scalarNode('name_pattern')
	                                ->end()
	                            ->end()
	                        ->end()
	                        ->arrayNode('bundle')
	                            ->children()
	                                ->scalarNode('name')
	                                ->end()
	                                ->scalarNode('namespace')
	                                ->end()
	                                ->scalarNode('dir')
	                                ->end()
	                            ->end()
	                        ->end()
	                        ->arrayNode('controller')
	                            ->children()
	                                ->scalarNode('namespace')
	                                ->end()
	                                ->scalarNode('class')
	                                ->end()
	                            ->end()
	                        ->end()
	                        ->arrayNode('handler')
	                            ->children()
	                                ->scalarNode('namespace')
	                                ->end()
	                            ->end()
	                        ->end()
	                        ->arrayNode('dto')
	                            ->children()
	                                ->scalarNode('namespace')
	                                ->end()
	                            ->end()
	                        ->end()
	                        ->arrayNode('store')
	                            ->useAttributeAsKey('namespace')
	                            ->prototype('scalar')
	                            ->end()
	                        ->end()
	                    ->end()
	                ->end()
	            ->end()
	        ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
