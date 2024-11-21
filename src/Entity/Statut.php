<?php

namespace App\Entity;

use App\Repository\StatutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatutRepository::class)]
class Statut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    /**
     * @var Collection<int, CommentairesStatut>
     */
    #[ORM\OneToMany(targetEntity: CommentairesStatut::class, mappedBy: 'statut')]
    private Collection $commentairesStatuts;

    #[ORM\ManyToOne(inversedBy: 'statuts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->commentairesStatuts = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, CommentairesStatut>
     */
    public function getCommentairesStatuts(): Collection
    {
        return $this->commentairesStatuts;
    }

    public function addCommentairesStatut(CommentairesStatut $commentairesStatut): static
    {
        if (!$this->commentairesStatuts->contains($commentairesStatut)) {
            $this->commentairesStatuts->add($commentairesStatut);
            $commentairesStatut->setStatut($this);
        }

        return $this;
    }

    public function removeCommentairesStatut(CommentairesStatut $commentairesStatut): static
    {
        if ($this->commentairesStatuts->removeElement($commentairesStatut)) {
            // set the owning side to null (unless already changed)
            if ($commentairesStatut->getStatut() === $this) {
                $commentairesStatut->setStatut(null);
            }
        }

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
