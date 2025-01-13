<?php

declare(strict_types=1);

namespace Chanondb\CookieConsentBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "cookieconsent_log")]
class CookieConsentLog
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $ipAddress;

    #[ORM\Column(type: "string", length: 255)]
    private string $cookieConsentKey;

    #[ORM\Column(type: "string", length: 255)]
    private string $cookieName;

    #[ORM\Column(type: "boolean")]
    private bool $cookieValue;

    #[ORM\Column(type: "datetime")]
    private DateTime $timestamp;

    public function getId(): int
    {
        return $this->id;
    }

    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function setCookieConsentKey(string $cookieConsentKey): self
    {
        $this->cookieConsentKey = $cookieConsentKey;

        return $this;
    }

    public function getCookieConsentKey(): string
    {
        return $this->cookieConsentKey;
    }

    public function setCookieName(string $cookieName): self
    {
        $this->cookieName = $cookieName;

        return $this;
    }

    public function getCookieName(): string
    {
        return $this->cookieName;
    }

    public function setCookieValue(bool $cookieValue): self
    {
        $this->cookieValue = $cookieValue;

        return $this;
    }

    public function getCookieValue(): bool
    {
        return $this->cookieValue;
    }

    public function setTimestamp(DateTime $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }
}