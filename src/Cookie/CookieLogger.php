<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace Chanondb\CookieConsentBundle\Cookie;

use Chanondb\CookieConsentBundle\Entity\CookieConsentLog;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class CookieLogger
{
    /**
     * @param list<string> $cookieCategories
     */
    public function __construct(
        private readonly ManagerRegistry $registry,
        private readonly ?Request $request,
        private readonly array $cookieCategories,
    ) {
    }

    public function log(array $categories, string $key): void
    {
        if ($this->request === null) {
            throw new \RuntimeException('No request found');
        }

        $entityManager = $this->registry->getManagerForClass(CookieConsentLog::class)
            ?? $this->registry->getManager();

        $ip = $this->anonymizeIp($this->request->getClientIp());

        foreach ($categories as $category => $value) {
            if (!\is_string($category) || !\in_array($category, $this->cookieCategories, true)) {
                continue;
            }
            $boolValue = $value === 'true' || $value === true;
            $this->persistCookieConsentLog($entityManager, $category, $boolValue, $ip, $key);
        }

        $entityManager->flush();
    }

    protected function persistCookieConsentLog(EntityManagerInterface $entityManager, string $category, bool $value, string $ip, string $key): void
    {
        $cookieConsentLog = (new CookieConsentLog())
            ->setIpAddress($ip)
            ->setCookieConsentKey($key)
            ->setCookieName($category)
            ->setCookieValue($value)
            ->setTimestamp(new \DateTime());

        $entityManager->persist($cookieConsentLog);
    }

    protected function anonymizeIp(?string $ip): string
    {
        if ($ip === null) {
            return 'unknown';
        }

        $lastDot = strrpos($ip, '.');
        if ($lastDot === false) {
            return 'unknown';
        }

        return substr($ip, 0, $lastDot + 1).str_repeat('x', \strlen($ip) - $lastDot - 1);
    }
}
