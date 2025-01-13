<?php

declare(strict_types=1);
namespace Chanondb\CookieConsentBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class CookieConsentExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        // Log entry into the load method
        dump('Entering CookieConsentExtension::load');

        // Process configuration
        $configuration = new Configuration();
        $processedConfig = $this->processConfiguration($configuration, $configs);

        // Dump the processed configuration
        dump('Processed Config:', $processedConfig);

        // Set parameters in the container
        $container->setParameter('cookie_consent.categories', $processedConfig['categories']);
        $container->setParameter('cookie_consent.use_logger', $processedConfig['use_logger']);
        $container->setParameter('cookie_consent.http_only', $processedConfig['http_only']);
        $container->setParameter('cookie_consent.form_action', $processedConfig['form_action']);
        $container->setParameter('cookie_consent.csrf_protection', $processedConfig['csrf_protection']);
        $container->setParameter('cookie_consent.disabled_routes', $processedConfig['disabled_routes']);

        // Dump the parameters to confirm they were set
        dump('Parameters set in the container:');
        dump($container->getParameterBag()->all());

        // Load service definitions
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        // Log that services.yaml was loaded
        dump('Loaded services.yaml');

        // Dump the container services to ensure they're registered
        dump('Registered services:', $container->getDefinitions());

        // Exit message
        dump('Exiting CookieConsentExtension::load');
    }

    public function getAlias(): string
    {
        return 'cookie_consent';
    }
}