<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace Chanondb\CookieConsentBundle;

use Chanondb\CookieConsentBundle\DependencyInjection\CookieConsentExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Symfony7CookieConsentBundle extends Bundle
{

    
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new CookieConsentExtension();
    }
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
