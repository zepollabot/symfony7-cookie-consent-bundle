<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace Chanondb\CookieConsentBundle\Cookie;

use Chanondb\CookieConsentBundle\Enum\CookieNameEnum;
use DateInterval;
use DateTime;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class CookieHandler
{
    /**
     * @param list<string> $cookieCategories
     */
    public function __construct(
        private readonly bool $httpOnly,
        private readonly array $cookieCategories,
    ) {
    }

    public function save(array $categories, string $key, Response $response): void
    {
        $this->saveCookie(CookieNameEnum::COOKIE_CONSENT_NAME, date('r'), $response);
        $this->saveCookie(CookieNameEnum::COOKIE_CONSENT_KEY_NAME, $key, $response);

        foreach ($categories as $category => $permitted) {
            if (!\is_string($category) || !\in_array($category, $this->cookieCategories, true)) {
                continue;
            }
            $stringValue = $permitted === true ? 'true' : 'false';
            $this->saveCookie(CookieNameEnum::getCookieCategoryName($category), $stringValue, $response);
        }
    }

    protected function saveCookie(string $name, string $value, Response $response): void
    {
        $expirationDate = new DateTime();
        $expirationDate->add(new DateInterval('P1Y'));

        $response->headers->setCookie(Cookie::create(
            name: $name,
            value: $value,
            expire: $expirationDate,
            path: '/',
            domain: null,
            secure: null,
            httpOnly: $this->httpOnly,
            raw: false,
            sameSite: Cookie::SAMESITE_LAX,
        ));
    }
}
