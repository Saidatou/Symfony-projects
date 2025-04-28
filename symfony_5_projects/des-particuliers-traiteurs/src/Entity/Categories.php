<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
 * @Vich\Uploadable
 */
class Categories
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
     * @ORM\Column(type="string", length=255)
     */
    private $photo;

     /**
     * @Vich\UploadableField(mapping="categories", fileNameProperty="photo")
     */
    private $photoFile;

    
    /**
     * @ORM\OneToMany(targetEntity=Ventes::class, mappedBy="categories")
     */
    private $ventes;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $maj;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->ventes = new ArrayCollection();
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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPhotoFile(){
        return $this->photoFile;
    }
    
    

   


    /**
     * @return Collection|Ventes[]
     */
    public function getVentes(): Collection
    {
        return $this->ventes;
    }

    public function addVente(Ventes $vente): self
    {
        if (!$this->ventes->contains($vente)) {
            $this->ventes[] = $vente;
            $vente->setCategories($this);
        }

        return $this;
    }

    public function removeVente(Ventes $vente): self
    {
        if ($this->ventes->removeElement($vente)) {
            // set the owning side to null (unless already changed)
            if ($vente->getCategories() === $this) {
                $vente->setCategories(null);
            }
        }

        return $this;
    }

    public function getMaj(): ?\DateTimeInterface
    {
        return $this->maj;
    }

    public function setMaj(?\DateTimeInterface $maj): self
    {
        $this->maj = $maj;

        return $this;
    }
}
