<?php

declare(strict_types=1);

namespace Chanondb\CookieConsentBundle;

use Chanondb\CookieConsentBundle\DependencyInjection\CookieConsentExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Symfony7CookieConsentBundle extends Bundle
{
    // /**
    //  * Return the bundle's container extension.
    //  */
    // public function getContainerExtension(): ?ExtensionInterface
    // {
    //     // Return an instance of the custom extension
    //     if (null === $this->extension) {
    //         $this->extension = new CookieConsentExtension();
    //     }

    //     return $this->extension;
    // }

    // /**
    //  * Return the bundle's root directory path.
    //  */
    // public function getPath(): string
    // {
    //     return \dirname(__DIR__);
    // }
}