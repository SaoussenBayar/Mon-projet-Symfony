<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ingredients = null;
    
    #[ORM\Column(type: Types::TEXT)]
    private ?string $detail = null;

    #[ORM\Column(length: 120)]
    private ?string $image = null;

    #[ORM\Column]
    private ?int $age_recommende = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_mise_ajour = null;

    /**
     * @var Collection<int, CommentairesRecette>
     */
    #[ORM\OneToMany(targetEntity: CommentairesRecette::class, mappedBy: 'recette')]
    private Collection $commentairesRecettes;

    public function __construct()
    {
        $this->commentairesRecettes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(string $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }   

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): static
    {
        $this->detail = $detail;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }


    public function getAgeRecommende(): ?int
    {
        return $this->age_recommende;
    }

    public function setAgeRecommende(int $age_recommende): static
    {
        $this->age_recommende = $age_recommende;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): static
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDateMiseAjour(): ?\DateTimeInterface
    {
        return $this->date_mise_ajour;
    }

    public function setDateMiseAjour(\DateTimeInterface $date_mise_ajour): static
    {
        $this->date_mise_ajour = $date_mise_ajour;

        return $this;
    }

    /**
     * @return Collection<int, CommentairesRecette>
     */
    public function getCommentairesRecettes(): Collection
    {
        return $this->commentairesRecettes;
    }

    public function addCommentairesRecette(CommentairesRecette $commentairesRecette): static
    {
        if (!$this->commentairesRecettes->contains($commentairesRecette)) {
            $this->commentairesRecettes->add($commentairesRecette);
            $commentairesRecette->setRecette($this);
        }

        return $this;
    }

    public function removeCommentairesRecette(CommentairesRecette $commentairesRecette): static
    {
        if ($this->commentairesRecettes->removeElement($commentairesRecette)) {
            // set the owning side to null (unless already changed)
            if ($commentairesRecette->getRecette() === $this) {
                $commentairesRecette->setRecette(null);
            }
        }

        return $this;
    }
}
