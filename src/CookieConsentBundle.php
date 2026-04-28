<?php

declare(strict_types=1);

namespace Chanondb\CookieConsentBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CookieConsentBundle extends Bundle
{
    /**
     * Bundle class lives in `src/`; config and Resources are under `src/Resources/`.
     */
    public function getPath(): string
    {
        return __DIR__;
    }
}