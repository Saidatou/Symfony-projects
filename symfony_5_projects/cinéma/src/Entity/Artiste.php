<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArtisteRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ArtisteRepository::class)
 * @Vich\Uploadable
 */
class Artiste
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo;
    /**
     * @Vich\UploadableField(mapping="portrait_config",fileNameProperty="photo")
     */
    private $photoFile;

    /**
     * @ORM\OneToMany(targetEntity=Film::class, mappedBy="realisateur")
     */
    private $filmsRealises;

    /**
     * @ORM\ManyToMany(targetEntity=Film::class, mappedBy="acteurs")
     */
    private $filmsJoues;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $maj;

    public function __construct()
    {
        $this->filmsRealises = new ArrayCollection();
        $this->filmsJoues = new ArrayCollection();
    }

    public function __toString(){
        return $this->getPrenom()." ".$this->getNom();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

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

    /**
     * @return Collection|Film[]
     */
    public function getFilmsRealises(): Collection
    {
        return $this->filmsRealises;
    }

    public function addFilmsRealise(Film $filmsRealise): self
    {
        if (!$this->filmsRealises->contains($filmsRealise)) {
            $this->filmsRealises[] = $filmsRealise;
            $filmsRealise->setRealisateur($this);
        }

        return $this;
    }

    public function removeFilmsRealise(Film $filmsRealise): self
    {
        if ($this->filmsRealises->removeElement($filmsRealise)) {
            // set the owning side to null (unless already changed)
            if ($filmsRealise->getRealisateur() === $this) {
                $filmsRealise->setRealisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Film[]
     */
    public function getFilmsJoues(): Collection
    {
        return $this->filmsJoues;
    }

    public function addFilmsJoue(Film $filmsJoue): self
    {
        if (!$this->filmsJoues->contains($filmsJoue)) {
            $this->filmsJoues[] = $filmsJoue;
            $filmsJoue->addActeur($this);
        }

        return $this;
    }

    public function removeFilmsJoue(Film $filmsJoue): self
    {
        if ($this->filmsJoues->removeElement($filmsJoue)) {
            $filmsJoue->removeActeur($this);
        }

        return $this;
    }

    public function getPhotoFile(){
        return $this->photoFile;
    }
    public function setPhoteFile(File $truc){
        $this->photoFile=$truc;
        if($truc !== null ){
            $this->updatedAt = new \DateTimeImmutable();
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
