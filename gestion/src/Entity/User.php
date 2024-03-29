<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
// use Doctrine\Common\Collections\Collection;
// use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Ce mail existe déjà')]// Spécifie que le mail est unique (généré auto)
#[UniqueEntity(fields: ['pseudo'], message: 'Ce pseudo existe déjà')]// Spécifie que le pseudo est unique (à ajouter)


class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)] // Pour spécifier que le mail est unique (généré auto)
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 100, unique: true)] // Pour spécifier que le pseudo est unique (à ajouter)
    private ?string $pseudo = null;

    // #[ORM\OneToMany(mappedBy: 'user', targetEntity: Stagiaire::class)]
    // private Collection $stagiaires;

    // public function __construct()
    // {
    //     $this->stagiairess = new ArrayCollection();
    // }

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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
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
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    ////////////////////////////////////////////////////////////////////////
    // Il est possible de créer d'autres fonctions ici

    // public function __toString() {          // Pour faciliter l'affichage des autres informations d'une entité

    //     return $this->Pseudo. " ";          // L'élément affiché de la liste des collections est seulement l'intitule
    // } 

    // /**
    // * @return Collection<int, Stagiaire>
    // */
    // public function getStagiaires(): Collection
    // {
    //     return $this->stagiaires;
    // }

    // public function addStagiaire(Stagiaire $stagiaire): static
    // {
    //     if (!$this->stagiaires->contains($stagiaire)) {
    //         $this->stagiaires->add($stagiaire);
    //         $stagiaire->setUser($this);
    //     }

    //     return $this;
    // }

    // public function removeStagiaire(Stagiaire $stagiaire): static
    // {
    //     if ($this->stagiaires->removeElement($stagiaire)) {
    //         // set the owning side to null (unless already changed)
    //         if ($stagiaire->getUser() === $this) {
    //             $stagiaire->setUser(null);
    //         }
    //     }

    //     return $this;
    // }

}
