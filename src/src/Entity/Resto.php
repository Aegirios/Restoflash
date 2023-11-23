<?php

namespace App\Entity;

use App\Repository\RestoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestoRepository::class)]
class Resto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $emailSender = null;

    #[ORM\Column(length: 255)]
    private ?string $lang = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slogan = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactMail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gps = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isReservationPossible = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $cgu = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $confidentialityPolicy = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $reservationPolicy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmailSender(): ?string
    {
        return $this->emailSender;
    }

    public function setEmailSender(string $emailSender): static
    {
        $this->emailSender = $emailSender;

        return $this;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(string $lang): static
    {
        $this->lang = $lang;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(?string $slogan): static
    {
        $this->slogan = $slogan;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getContactMail(): ?string
    {
        return $this->contactMail;
    }

    public function setContactMail(?string $contactMail): static
    {
        $this->contactMail = $contactMail;

        return $this;
    }

    public function getGps(): ?string
    {
        return $this->gps;
    }

    public function setGps(?string $gps): static
    {
        $this->gps = $gps;

        return $this;
    }

    public function isIsReservationPossible(): ?bool
    {
        return $this->isReservationPossible;
    }

    public function setIsReservationPossible(?bool $isReservationPossible): static
    {
        $this->isReservationPossible = $isReservationPossible;

        return $this;
    }

    public function getCgu(): ?string
    {
        return $this->cgu;
    }

    public function setCgu(?string $cgu): static
    {
        $this->cgu = $cgu;

        return $this;
    }

    public function getConfidentialityPolicy(): ?string
    {
        return $this->confidentialityPolicy;
    }

    public function setConfidentialityPolicy(?string $confidentialityPolicy): static
    {
        $this->confidentialityPolicy = $confidentialityPolicy;

        return $this;
    }

    public function getReservationPolicy(): ?string
    {
        return $this->reservationPolicy;
    }

    public function setReservationPolicy(?string $reservationPolicy): static
    {
        $this->reservationPolicy = $reservationPolicy;

        return $this;
    }
}
