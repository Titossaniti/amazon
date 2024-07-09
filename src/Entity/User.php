<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
const ROLE_USER = 'ROLE_USER';
const ROLE_COMMERCANT = 'ROLE_COMMERCANT';

#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column]
private ?int $id = null;

#[ORM\Column(length: 180)]
private ?string $email = null;

/**
* @var list<string> The user roles
    */
    #[ORM\Column]
    private array $roles = [];

    /**
    * @var string The hashed password
    */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    /**
    * @var Collection<int, Article>
    */
    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'username')]
    private Collection $article;

    /**
    * @var Collection<int, Commande>
    */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'user')]
    private Collection $commande;

    #[ORM\OneToOne(inversedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Panier $panier = null;

    #[ORM\Column]
    private bool $isVerified = false;

    public function __construct()
    {
    $this->roles = [];
    $this->article = new ArrayCollection();
    $this->commande = new ArrayCollection();
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
    *
    * @return list<string>
    */
    public function getRoles(): array
    {
    $roles = $this->roles;
    if (!in_array(self::ROLE_USER, $roles)) {
    $roles[] = self::ROLE_USER;
    }

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

        public function getUsername(): ?string
        {
        return $this->username;
        }

        public function setUsername(string $username): static
        {
        $this->username = $username;

        return $this;
        }

        /**
        * @return Collection<int, Article>
        */
        public function getArticle(): Collection
        {
        return $this->article;
        }

        public function addArticle(Article $article): static
        {
        if (!$this->article->contains($article)) {
        $this->article->add($article);
        $article->setUser($this);
        }

        return $this;
        }

        public function removeArticle(Article $article): static
        {
        if ($this->article->removeElement($article)) {
        // set the owning side to null (unless already changed)
        if ($article->getUser() === $this) {
        $article->setUser(null);
        }
        }

        return $this;
        }

        /**
        * @return Collection<int, Commande>
        */
        public function getCommande(): Collection
        {
        return $this->commande;
        }

        public function addCommande(Commande $commande): static
        {
        if (!$this->commande->contains($commande)) {
        $this->commande->add($commande);
        $commande->setUser($this);
        }

        return $this;
        }

        public function removeCommande(Commande $commande): static
        {
        if ($this->commande->removeElement($commande)) {
        // set the owning side to null (unless already changed)
        if ($commande->getUser() === $this) {
        $commande->setUser(null);
        }
        }

        return $this;
        }

        public function getPanier(): ?Panier
        {
        return $this->panier;
        }

        public function setPanier(?Panier $panier): static
        {
        $this->panier = $panier;

        return $this;
        }

        public function isVerified(): bool
        {
        return $this->isVerified;
        }

        public function setVerified(bool $isVerified): static
        {
        $this->isVerified = $isVerified;

        return $this;
        }
}
