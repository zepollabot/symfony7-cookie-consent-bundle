<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace Chanondb\CookieConsentBundle\Cookie;

use Chanondb\CookieConsentBundle\Enum\CookieNameEnum;
use Symfony\Component\HttpFoundation\Request;

class CookieChecker
{
    public function __construct(
        private readonly ?Request $request,
    ) {
    }

    public function isCookieConsentSavedByUser(): bool
    {
        if ($this->request === null) {
            return false;
        }

        return $this->request->cookies->has(CookieNameEnum::COOKIE_CONSENT_NAME);
    }

    public function isCategoryAllowedByUser(string $category): bool
    {
        if ($this->request === null) {
            return false;
        }

        return $this->request->cookies->get(CookieNameEnum::getCookieCategoryName($category)) === 'true';
    }
}
