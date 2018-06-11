<?php

namespace Base\EventListener;

use Base\Service\TranslationService;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleListener implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    private $translationService;
    private $defaultLocale;
    private $locales;

    public function __construct(TranslationService $translationService, array $locales, string $defaultLocale)
    {
        $this->translationService = $translationService;
        $this->locales = $locales;
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $userLocale = $request->cookies->get('locale');

        if ($userLocale === null) {
            $userLocale = $request->getPreferredLanguage($this->locales) ?? $this->defaultLocale;
        }

        $this->translationService->setLocale($userLocale);
    }

    public static function getSubscribedEvents()
    {
        return [KernelEvents::REQUEST => 'onKernelRequest'];
    }
}