<?php

namespace App\Entity;

use App\Repository\MoisRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoisRepository::class)
 */
class Mois
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Fruit::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $fruit;

   



    public function __toString() {
        return $this->getNom();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getFruit(): ?Fruit
    {
        return $this->fruit;
    }

    public function setFruit(?Fruit $fruit): self
    {
        $this->fruit = $fruit;

        return $this;
    }
}
