<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace Chanondb\CookieConsentBundle\Tests\DependencyInjection;

use Chanondb\CookieConsentBundle\DependencyInjection\CookieConsentExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

class CookieConsentExtensionTest extends TestCase
{
    /**
     * @var CookieConsentExtension
     */
    private $cookieConsentExtension;

    /**
     * @var ContainerBuilder
     */
    private $configuration;

    public function setUp(): void
    {
        $this->cookieConsentExtension = new CookieConsentExtension();
        $this->configuration            = new ContainerBuilder();
    }

    public function testFullConfiguration(): void
    {
        $this->createConfiguration($this->getFullConfig());

        $this->assertParameter(['analytics', 'marketing', 'preferences'], 'cookie_consent.categories');
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testInvalidConfiguration(): void
    {
        $this->createConfiguration($this->getInvalidConfig());
    }

    /**
     * create configuration.
     */
    protected function createConfiguration(array $config): void
    {
        $this->cookieConsentExtension->load([$config], $this->configuration);

        $this->assertTrue($this->configuration instanceof ContainerBuilder);
    }

    /**
     * get full config.
     */
    protected function getFullConfig(): array
    {
        $yaml = <<<EOF
categories: ['analytics', 'marketing', 'preferences']
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    /**
     * get invalid config.
     */
    protected function getInvalidConfig(): array
    {
        $yaml = <<<EOF
theme: 'not_existing'
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    /**
     * Test if parameter is set.
     */
    private function assertParameter($value, $key): void
    {
        $this->assertSame($value, $this->configuration->getParameter($key), sprintf('%s parameter is correct', $key));
    }
}
