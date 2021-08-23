<?php

namespace App\Entity;

use App\Repository\ParamsRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields="email", message="Email déjà utilisé")
 * @UniqueEntity(fields="name", message="Nom déjà utilisé")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Cart::class, mappedBy="user", orphanRemoval=true)
     */
    private $carts;

    /**
     * @ORM\ManyToMany(targetEntity=Film::class, mappedBy="users")
     */
    private $films;

    /**
     * @ORM\ManyToMany(targetEntity=Film::class)
     */
    private $filmsNotRender;

    /**
     * @ORM\ManyToMany(targetEntity=Film::class, mappedBy="currentUsers")
     */
    private $currentFilms;

    /**
     * @ORM\OneToMany(targetEntity=Borrow::class, mappedBy="user", orphanRemoval=true)
     */
    private $borrows;

    public function __construct()
    {
        $this->carts = new ArrayCollection();
        $this->films = new ArrayCollection();
        $this->filmsNotRender = new ArrayCollection();
        $this->currentFilms = new ArrayCollection();
        $this->borrows = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
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

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Cart[]
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function getActiveCart()
    {
        foreach ($this->carts as $cart) {
            if ($cart->getIsActive()) {
                return $cart;
            }
        }
    }

    public function addCart(Cart $cart): self
    {
        if (!$this->carts->contains($cart)) {
            $this->carts[] = $cart;
            $cart->setUser($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getUser() === $this) {
                $cart->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Film[]
     */
    public function getFilms(): Collection
    {
        return $this->films;
    }

    public function addFilm(Film $film): self
    {
        if (!$this->films->contains($film)) {
            $this->films[] = $film;
            $film->addUser($this);
        }

        return $this;
    }

    public function removeFilm(Film $film): self
    {
        if ($this->films->removeElement($film)) {
            $film->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Film[]
     */
    public function getFilmsNotRender(): Collection
    {
        return $this->filmsNotRender;
    }

    public function addFilmsNotRender(Film $filmsNotRender): self
    {
        if (!$this->filmsNotRender->contains($filmsNotRender)) {
            $this->filmsNotRender[] = $filmsNotRender;
        }

        return $this;
    }

    public function removeFilmsNotRender(Film $filmsNotRender): self
    {
        $this->filmsNotRender->removeElement($filmsNotRender);

        return $this;
    }

    /**
     * @return Collection|Film[]
     */
    public function getCurrentFilms(): Collection
    {
        return $this->currentFilms;
    }

    public function addCurrentFilm(Film $currentFilm): self
    {
        if (!$this->currentFilms->contains($currentFilm)) {
            $this->currentFilms[] = $currentFilm;
            $currentFilm->addCurrentUser($this);
        }

        return $this;
    }

    public function removeCurrentFilm(Film $currentFilm): self
    {
        if ($this->currentFilms->removeElement($currentFilm)) {
            $currentFilm->removeCurrentUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Borrow[]
     */
    public function getBorrows(): Collection
    {
        return $this->borrows;
    }

    public function addBorrow(Borrow $borrow): self
    {
        if (!$this->borrows->contains($borrow)) {
            $this->borrows[] = $borrow;
            $borrow->setUser($this);
        }

        return $this;
    }

    public function removeBorrow(Borrow $borrow): self
    {
        if ($this->borrows->removeElement($borrow)) {
            // set the owning side to null (unless already changed)
            if ($borrow->getUser() === $this) {
                $borrow->setUser(null);
            }
        }

        return $this;
    }

    public function isMaxBorrow($paramRepo)
    {
        $params = $paramRepo->find(1);
        $borrows = $this->getBorrows();
        $count = 0;

        foreach ($borrows as $borrow) {
            if ($borrow->getFilms()->count() > 0) {
                $count++;
            }
        }

        if ($count >= $params->getMaxBorrowByUser()) {
            return true;
        } else {
            return false;
        }
    }
}
