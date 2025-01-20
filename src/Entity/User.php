<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */

    /**
     * @Assert\NotBlank(message="Le mot de passe ne peut pas être vide.")
     * @Assert\Length(
     *     min=12,
     *     minMessage="Le mot de passe doit contenir au moins {{ limit }} caractères."
     * )
     * @Assert\Regex(
     *     pattern="/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/",
     *     message="Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial."
     * )
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $Prenom = null;

   #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date_naissance = null;

   #[ORM\Column(length: 255)]
   private ?string $ville = null;

   #[ORM\Column(length: 255)]
   private ?string $Pays = null;

   #[ORM\Column(type: 'string', length: 255, nullable: true)]
   private ?string $pseudo = null;

   public function getPseudo(): ?string
   {
       return $this->pseudo;
   }

   public function setPseudo(?string $pseudo): self
   {
       $this->pseudo = $pseudo;
       return $this;
   }

   #[ORM\Column(type: Types::DATETIME_MUTABLE)]
   private ?\DateTimeInterface $Date_inscription = null;

   private $isVerified = false;

   // Getter
   public function getIsVerified(): bool
   {
       return $this->isVerified;
   }

   // Setter
   public function setIsVerified(bool $isVerified): self
   {
       $this->isVerified = $isVerified;
       return $this;
   }

  
    // @var Collection<int, CommentairesRecette>
    
    #[ORM\OneToMany(targetEntity: CommentairesRecette::class, mappedBy: 'user')]
    private Collection $commentairesRecettes; 

    /**
     * @var Collection<int, CommentairesStatut>
     */
    #[ORM\OneToMany(targetEntity: CommentairesStatut::class, mappedBy: 'user')]
    private Collection $commentairesStatuts;

    /**
     * @var Collection<int, Statut>
     */
    #[ORM\OneToMany(targetEntity: Statut::class, mappedBy: 'user')]
    private Collection $statuts;

    /**
     * @var Collection<int, Jouets>
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $resetToken = null;

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

   
  

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */ 
    

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }


    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): static
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->Date_naissance;
    }

    public function setDateNaissance(\DateTimeInterface $Date_naissance): static
    {
        $this->Date_naissance = $Date_naissance;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->Pays;
    }  

    public function setPays(string $Pays): static
    {
        $this->Pays = $Pays;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->Date_inscription;
    }

    public function setDateInscription(\DateTimeInterface $Date_inscription): static
    {
        $this->Date_inscription = $Date_inscription;

        return $this;
    }

    /**
     * @return Collection<int, CommentairesRecette>
     */ /* */
    
    public function getCommentairesRecettes(): Collection
    {
        return $this->commentairesRecettes;
    }

    public function addCommentairesRecette(CommentairesRecette $commentairesRecette): static
    {
        if (!$this->commentairesRecettes->contains($commentairesRecette)) {
            $this->commentairesRecettes->add($commentairesRecette);
            $commentairesRecette->setUser($this);
        }

        return $this;
    }

    public function removeCommentairesRecette(CommentairesRecette $commentairesRecette): static
    {
        if ($this->commentairesRecettes->removeElement($commentairesRecette)) {
            // set the owning side to null (unless already changed)
            if ($commentairesRecette->getUser() === $this) {
                $commentairesRecette->setUser(null);
            }
        }

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
            $commentairesStatut->setUser($this);
        }

        return $this;
    }

    public function removeCommentairesStatut(CommentairesStatut $commentairesStatut): static
    {
        if ($this->commentairesStatuts->removeElement($commentairesStatut)) {
            // set the owning side to null (unless already changed)
            if ($commentairesStatut->getUser() === $this) {
                $commentairesStatut->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Statut>
     */
    
    public function getStatuts(): Collection
    {
        return $this->statuts;
    }

    public function addStatut(Statut $statut): static
    {
        if (!$this->statuts->contains($statut)) {
            $this->statuts->add($statut);
            $statut->setUser($this);
        }

        return $this;
    }

    public function removeStatut(Statut $statut): static
    {
        if ($this->statuts->removeElement($statut)) {
            // set the owning side to null (unless already changed)
            if ($statut->getUser() === $this) {
                $statut->setUser(null);
            }
        }

        return $this;
    }

    
}
