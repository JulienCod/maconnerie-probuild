<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContactRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: "Le champs nom / prénom ne peut pas être vide")]
    #[Assert\Length(min: 5, max: 180, minMessage:'Le champ nom / prénom doit contenir au moins {{ limit }} caractères.', maxMessage:'Le champ nom / prénom doit contenir moins de {{ limit }} caractères.')]
    private string $fullName;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: "Le champs email ne peut pas être vide")]
    #[Assert\Length(min: 5, max: 180, minMessage:"L'email ne peut pas contenir moins de {{ limit }} caractères", maxMessage:"L'email ne peut pas contenir plus de {{limit}} caractères")]
    #[Assert\Email(message: "Le champs doit absolument être une adresse mail valide")]

    private string $email;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champs objet ne peut pas être vide")]
    #[Assert\Length(min: 5, max: 255, minMessage:"L'objet ne peut pas contenir moins de {{limit}} caractères", maxMessage:"L'objet ne peut pas contenir plus de {{limit}} caractères")]
    
    
    private string $subject;
    
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(min: 100, max: 1000, minMessage:"Le message ne peut pas contenir moins de {{limit}} caractères", maxMessage:"Le message ne peut pas contenir plus de {{limit}} caractères")]
    #[Assert\NotBlank(message: "Le champs message ne peut pas être vide")]

    private string $content;

    #[ORM\Column(length: 20)]
    #[Assert\Regex(pattern:"#^(?:\+|[0-9])\d*$#", message:'Le champs numéro de téléphone doit être composer de chiffres uniquement pour le format international le "+" est autorisé')]
    #[Assert\Length(min: 10, max: 20, minMessage:'Le numéro de téléphone doit contenir au minimum {{limit}} caractères', maxMessage:'Le numéro de téléphone doit contenir au maximum {{limit}} caractères')]
    private string $phoneNumber;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt;

    #[ORM\Column]
    #[Assert\NotBlank(message:'Veuillez accepté les conditions de stockage de vos données saisie dans ce formulaire')]
    private bool $rgpd;

    #[ORM\Column(length: '255', nullable: true)]
    private ?string $honeypot = null;

    #[ORM\Column(length: 255)]
    private ?string $securityQuestion = null;


    public function __construct()
    {
        $this->setCreatedAt(new DateTimeImmutable());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function isRgpd(): ?bool
    {
        return $this->rgpd;
    }

    public function setRgpd(bool $rgpd): static
    {
        $this->rgpd = $rgpd;

        return $this;
    }

    public function getHoneypot(): ?string
    {
        return $this->honeypot;
    }

    public function setHoneypot(?string $honeypot): static
    {
        $this->honeypot = $honeypot;

        return $this;
    }

    public function getSecurityQuestion(): ?string
    {
        return $this->securityQuestion;
    }

    public function setSecurityQuestion(string $securityQuestion): static
    {
        $this->securityQuestion = $securityQuestion;

        return $this;
    }
}
