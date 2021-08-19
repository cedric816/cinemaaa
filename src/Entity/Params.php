<?php

namespace App\Entity;

use App\Repository\ParamsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParamsRepository::class)
 */
class Params
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxFilmByBorrow;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxBorrowByUser;

    /**
     * @ORM\Column(type="integer")
     */
    private $borrowLenght;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaxFilmByBorrow(): ?int
    {
        return $this->maxFilmByBorrow;
    }

    public function setMaxFilmByBorrow(int $maxFilmByBorrow): self
    {
        $this->maxFilmByBorrow = $maxFilmByBorrow;

        return $this;
    }

    public function getMaxBorrowByUser(): ?int
    {
        return $this->maxBorrowByUser;
    }

    public function setMaxBorrowByUser(int $maxBorrowByUser): self
    {
        $this->maxBorrowByUser = $maxBorrowByUser;

        return $this;
    }

    public function getBorrowLenght(): ?int
    {
        return $this->borrowLenght;
    }

    public function setBorrowLenght(int $borrowLenght): self
    {
        $this->borrowLenght = $borrowLenght;

        return $this;
    }
}
