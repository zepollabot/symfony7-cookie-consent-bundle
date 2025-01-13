<?php

declare(strict_types=1);namespace Chanondb\CookieConsentBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class CookieConsentExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        // Writing to the console
        echo "Loading CookieConsentExtension...\n";

        // Process configuration
        $configuration = new Configuration();
        $processedConfig = $this->processConfiguration($configuration, $configs);

        // Log processed configuration
        echo "Processed Config: " . print_r($processedConfig, true) . "\n";

        // Set parameters in the container
        $container->setParameter('cookie_consent.categories', $processedConfig['categories']);
        $container->setParameter('cookie_consent.use_logger', $processedConfig['use_logger']);
        $container->setParameter('cookie_consent.http_only', $processedConfig['http_only']);
        $container->setParameter('cookie_consent.form_action', $processedConfig['form_action']);
        $container->setParameter('cookie_consent.csrf_protection', $processedConfig['csrf_protection']);
        $container->setParameter('cookie_consent.disabled_routes', $processedConfig['disabled_routes']);

        // Writing parameters to the console
        echo "Parameters set in the container:\n";
        echo print_r($container->getParameterBag()->all(), true) . "\n";

        // Load services.yaml
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        // Writing services.yaml load to console
        echo "services.yaml loaded successfully.\n";

        // Dump registered services
        echo "Registered services:\n";
        echo print_r(array_keys($container->getDefinitions()), true) . "\n";
    }
}