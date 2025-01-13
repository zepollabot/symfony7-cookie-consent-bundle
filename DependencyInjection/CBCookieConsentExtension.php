<?php

declare(strict_types=1);

namespace Chanondb\CookieConsentBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Attribute\AsExtension;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

#[AsExtension(name: 'cb_cookie_consent')]
class CBCookieConsentExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('cb_cookie_consent.categories', $config['categories']);
        $container->setParameter('cb_cookie_consent.use_logger', $config['use_logger']);
        $container->setParameter('cb_cookie_consent.http_only', $config['http_only']);
        $container->setParameter('cb_cookie_consent.form_action', $config['form_action']);
        $container->setParameter('cb_cookie_consent.csrf_protection', $config['csrf_protection']);
        $container->setParameter('cb_cookie_consent.disabled_routes', $config['disabled_routes']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }
    public function getAlias(): string
    {
        return 'cb_cookie_consent';
    }
}
