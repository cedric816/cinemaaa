<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FilmRepository::class)
 */
class Film
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("film:read")
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @Groups("film:read")
     */
    private $year;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("film:read")
     */
    private $runtime;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("film:read")
     */
    private $director;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("film:read")
     */
    private $poster;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("film:read")
     */
    private $plot;

    /**
     * @ORM\Column(type="boolean")
     */
    private $available;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\ManyToMany(targetEntity=Cart::class, mappedBy="films")
     */
    private $carts;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="films")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="currentFilms")
     */
    // private $currentUsers;

    /**
     * @ORM\Column(type="integer")
     */
    private $startQuantity;

    /**
     * @ORM\ManyToMany(targetEntity=Borrow::class, mappedBy="films")
     */
    private $borrows;

    public function __construct()
    {
        $this->carts = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->currentUsers = new ArrayCollection();
        $this->borrows = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getRuntime(): ?int
    {
        return $this->runtime;
    }

    public function setRuntime(?int $runtime): self
    {
        $this->runtime = $runtime;

        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(string $director): self
    {
        $this->director = $director;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getPlot(): ?string
    {
        return $this->plot;
    }

    public function setPlot(?string $plot): self
    {
        $this->plot = $plot;

        return $this;
    }

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return Collection|Cart[]
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): self
    {
        if (!$this->carts->contains($cart)) {
            $this->carts[] = $cart;
            $cart->addFilm($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->removeElement($cart)) {
            $cart->removeFilm($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getCarts();
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getCurrentUsers(): Collection
    {
        return $this->currentUsers;
    }

    public function addCurrentUser(User $currentUser): self
    {
        if (!$this->currentUsers->contains($currentUser)) {
            $this->currentUsers[] = $currentUser;
        }

        return $this;
    }

    public function removeCurrentUser(User $currentUser): self
    {
        $this->currentUsers->removeElement($currentUser);

        return $this;
    }

    public function getStartQuantity(): ?int
    {
        return $this->startQuantity;
    }

    public function setStartQuantity(int $startQuantity): self
    {
        $this->startQuantity = $startQuantity;

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
            $borrow->addFilm($this);
        }

        return $this;
    }

    public function removeBorrow(Borrow $borrow): self
    {
        if ($this->borrows->removeElement($borrow)) {
            $borrow->removeFilm($this);
        }

        return $this;
    }
}
