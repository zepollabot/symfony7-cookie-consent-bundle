<?php

namespace Chanondb\CookieConsentBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('cookie_consent');

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('categories')
                    ->scalarPrototype()->end()
                    ->defaultValue(['necessary', 'preferences', 'analytics'])
                ->end()
                ->booleanNode('use_logger')->defaultTrue()->end()
                ->booleanNode('http_only')->defaultTrue()->end()
                ->scalarNode('form_action')->defaultNull()->end()
                ->booleanNode('csrf_protection')->defaultTrue()->end()
                ->arrayNode('disabled_routes')
                    ->scalarPrototype()->end()
                    ->defaultValue(['privacy', 'imprint'])
                ->end()
            ->end();

        return $treeBuilder;
    }
}