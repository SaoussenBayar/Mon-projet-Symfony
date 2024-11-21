<?php

namespace App\Entity;

use App\Repository\JouetsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JouetsRepository::class)]
class Jouets
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $id_echange = null;

    #[ORM\Column(length: 255)]
    private ?string $Titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $contact = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Date_publication = null;

    #[ORM\ManyToOne(inversedBy: 'jouets')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdEchange(): ?int
    {
        return $this->id_echange;
    }

    public function setIdEchange(int $id_echange): static
    {
        $this->id_echange = $id_echange;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): static
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->Date_publication;
    }

    public function setDatePublication(\DateTimeInterface $Date_publication): static
    {
        $this->Date_publication = $Date_publication;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
