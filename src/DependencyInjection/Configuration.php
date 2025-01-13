<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace Chanondb\CookieConsentBundle\DependencyInjection;

use Chanondb\CookieConsentBundle\Enum\CategoryEnum;
use Chanondb\CookieConsentBundle\Enum\DisabledRoutesEnum;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('cookie_consent');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = /* @scrutinizer ignore-deprecated */ $treeBuilder->root('cookie_consent');
        }

        $rootNode
            ->children()
                ->variableNode('categories')
                    ->defaultValue([CategoryEnum::CATEGORY_ANALYTICS, CategoryEnum::CATEGORY_MARKETING, CategoryEnum::CATEGORY_PREFERENCES])
                ->end()
                ->booleanNode('use_logger')
                    ->defaultTrue()
                ->end()
                ->booleanNode('http_only')
                    ->defaultTrue()
                ->end()
                ->scalarNode('form_action')
                    ->defaultNull()
                ->end()
                ->booleanNode('csrf_protection')
                    ->defaultTrue()
                ->end()
                ->variableNode('disabled_routes')
                    ->defaultValue([DisabledRoutesEnum::DISABLED_ROUTE_PRIVACY, DisabledRoutesEnum::DISABLED_ROUTE_IMPRINT])
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
