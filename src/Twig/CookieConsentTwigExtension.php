<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace Chanondb\CookieConsentBundle\Twig;

use Chanondb\CookieConsentBundle\Cookie\CookieChecker;
use Symfony\Component\HttpFoundation\Request;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CookieConsentTwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'cbcookieconsent_isCookieConsentSavedByUser',
                [$this, 'isCookieConsentSavedByUser'],
                ['needs_context' => true]
            ),
            new TwigFunction(
                'cbcookieconsent_isCategoryAllowedByUser',
                [$this, 'isCategoryAllowedByUser'],
                ['needs_context' => true]
            ),
            new TwigFunction(
                'cbcookieconsent_isCookieConsentOpenByDefault',
                [$this, 'isCookieConsentOpenByDefault'],
                ['needs_context' => true]
            ),
        ];
    }

    public function isCookieConsentOpenByDefault(array $context, string $currentRoute, array $disabledRoutes): string
    {
        return \in_array($currentRoute, $disabledRoutes, true) || $this->isCookieConsentSavedByUser($context) ? 'false' : 'true';
    }

    public function isCookieConsentSavedByUser(array $context): bool
    {
        $cookieChecker = $this->getCookieChecker($context['app']->getRequest());

        return $cookieChecker->isCookieConsentSavedByUser();
    }

    public function isCategoryAllowedByUser(array $context, string $category): bool
    {
        $cookieChecker = $this->getCookieChecker($context['app']->getRequest());

        return $cookieChecker->isCategoryAllowedByUser($category);
    }

    private function getCookieChecker(Request $request): CookieChecker
    {
        return new CookieChecker($request);
    }
}
