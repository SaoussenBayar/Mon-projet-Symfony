<?php

namespace App\Entity;

use App\Repository\CommentairesStatutRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentairesStatutRepository::class)]
class CommentairesStatut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Date_commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'commentairesStatuts')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'commentairesStatuts')]
    private ?Statut $statut = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateCommentaire(): ?\DateTimeInterface
    {
        return $this->Date_commentaire;
    }

    public function setDateCommentaire(\DateTimeInterface $Date_commentaire): static
    {
        $this->Date_commentaire = $Date_commentaire;

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

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): static
    {
        $this->statut = $statut;

        return $this;
    }
}
