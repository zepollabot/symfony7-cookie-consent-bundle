<?php

declare(strict_types=1);

namespace Chanondb\CookieConsentBundle\Controller;

use Chanondb\CookieConsentBundle\Cookie\CookieChecker;
use Chanondb\CookieConsentBundle\Form\CookieConsentType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class CookieConsentController
{
    private Environment $twigEnvironment;
    private FormFactoryInterface $formFactory;
    private CookieChecker $cookieChecker;
    private RouterInterface $router;
    private TranslatorInterface $translator;
    private ?string $formAction;
    private array $disabledRoutes;

    public function __construct(
        Environment $twigEnvironment,
        FormFactoryInterface $formFactory,
        CookieChecker $cookieChecker,
        RouterInterface $router,
        TranslatorInterface $translator,
        ?string $formAction = null,
        array $cookieConsentDisabledRoutes = []
    ) {
        $this->twigEnvironment = $twigEnvironment;
        $this->formFactory = $formFactory;
        $this->cookieChecker = $cookieChecker;
        $this->router = $router;
        $this->translator = $translator;
        $this->formAction = $formAction;
        $this->disabledRoutes = $cookieConsentDisabledRoutes;
    }

    #[Route('/cookie_consent', name: 'cookie_consent.show')]
    public function show(Request $request): Response
    {
        $this->setLocale($request);

        $response = new Response(
            $this->twigEnvironment->render('@CookieConsent/cookie_consent.html.twig', [
                'form' => $this->createCookieConsentForm()->createView(),
                'disabled_routes' => $this->disabledRoutes,
            ])
        );

        // Cache in ESI should not be shared
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
        $locale = $request->get('locale');
        if (!empty($locale)) {
            $this->translator->setLocale($locale);
            $request->setLocale($locale);
        }
    }
}