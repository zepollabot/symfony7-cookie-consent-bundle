<?php

declare(strict_types=1);

namespace Chanondb\CookieConsentBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class CookieConsentExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();

        // Process the configuration and set parameters
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('cookie_consent.categories', $config['categories']);
        $container->setParameter('cookie_consent.use_logger', $config['use_logger']);
        $container->setParameter('cookie_consent.http_only', $config['http_only']);
        $container->setParameter('cookie_consent.form_action', $config['form_action']);
        $container->setParameter('cookie_consent.csrf_protection', $config['csrf_protection']);
        $container->setParameter('cookie_consent.disabled_routes', $config['disabled_routes']);

        // Load service definitions
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }

    public function getAlias(): string
    {
        return 'cookie_consent';
    }
}