<?php

namespace App\Entity;

use App\Repository\CommentairesJeuxRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentairesJeuxRepository::class)]
class CommentairesJeux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\Column]
    private ?int $note = null;

    #[ORM\Column(length: 255)]
    private ?string $Date_commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'commentairesJeuxes')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'commentairesJeuxes')]
    private ?JeuxEDUC $jeux = null;

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

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getDateCommentaire(): ?string
    {
        return $this->Date_commentaire;
    }

    public function setDateCommentaire(string $Date_commentaire): static
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

    public function getJeux(): ?JeuxEDUC
    {
        return $this->jeux;
    }

    public function setJeux(?JeuxEDUC $jeux): static
    {
        $this->jeux = $jeux;

        return $this;
    }
}
