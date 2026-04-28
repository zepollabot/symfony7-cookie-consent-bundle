<?php

declare(strict_types=1);

namespace Chanondb\CookieConsentBundle\Controller;

use Chanondb\CookieConsentBundle\Cookie\CookieChecker;
use Chanondb\CookieConsentBundle\Form\CookieConsentType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Twig\Environment;

class CookieConsentController
{
    public function __construct(
        private readonly Environment $twigEnvironment,
        private readonly FormFactoryInterface $formFactory,
        private readonly CookieChecker $cookieChecker,
        private readonly RouterInterface $router,
        private readonly LocaleAwareInterface $translator,
        private readonly ?string $formAction = null,
        private readonly array $cookieConsentDisabledRoutes = [],
    ) {
    }

    #[Route('/cookie_consent', name: 'cookie_consent.show')]
    public function show(Request $request): Response
    {
        $this->setLocale($request);

        $response = new Response(
            $this->twigEnvironment->render('@CookieConsent/cookie_consent.html.twig', [
                'form' => $this->createCookieConsentForm()->createView(),
                'disabled_routes' => $this->cookieConsentDisabledRoutes,
            ])
        );

        $response->setPrivate();
        $response->setMaxAge(0);

        return $response;
    }

    #[Route('/cookie_consent_alt', name: 'cookie_consent.show_if_cookie_consent_not_set')]
    public function showIfCookieConsentNotSet(Request $request): Response
    {
        if ($this->cookieChecker->isCookieConsentSavedByUser() === false) {
            return $this->show($request);
        }

        return new Response();
    }

    protected function createCookieConsentForm(): FormInterface
    {
        if ($this->formAction === null) {
            return $this->formFactory->create(CookieConsentType::class);
        }

        return $this->formFactory->create(
            CookieConsentType::class,
            null,
            ['action' => $this->router->generate($this->formAction)]
        );
    }

    protected function setLocale(Request $request): void
    {
        $locale = $request->attributes->get('_locale') ?? $request->getLocale();
        if ($locale !== '') {
            $this->translator->setLocale((string) $locale);
            $request->setLocale((string) $locale);
        }
    }
}
