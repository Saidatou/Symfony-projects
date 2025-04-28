<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FilmRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass=FilmRepository::class)
 * @Vich\Uploadable
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
     */
    private $titre;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDeSortie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $affiche;

    /**
     * @Vich\UploadableField( mapping="affiche_config", fileNameProperty="affiche")
     */
    private $afficheFile;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Artiste::class, inversedBy="filmsRealises")
     * @ORM\JoinColumn(nullable=false)
     */
    private $realisateur;

    /**
     * @ORM\ManyToMany(targetEntity=Artiste::class, inversedBy="filmsJoues")
     */
    private $acteurs;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $maj;

    public function __construct()
    {
        $this->acteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateDeSortie(): ?\DateTimeInterface
    {
        return $this->dateDeSortie;
    }

    public function setDateDeSortie(\DateTimeInterface $dateDeSortie): self
    {
        $this->dateDeSortie = $dateDeSortie;

        return $this;
    }

    public function getAffiche(): ?string
    {
        return $this->affiche;
    }

    public function setAffiche(?string $affiche): self
    {
        $this->affiche = $affiche;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRealisateur(): ?Artiste
    {
        return $this->realisateur;
    }

    public function setRealisateur(?Artiste $realisateur): self
    {
        $this->realisateur = $realisateur;

        return $this;
    }

    /**
     * @return Collection|Artiste[]
     */
    public function getActeurs(): Collection
    {
        return $this->acteurs;
    }

    public function addActeur(Artiste $acteur): self
    {
        if (!$this->acteurs->contains($acteur)) {
            $this->acteurs[] = $acteur;
        }

        return $this;
    }

    public function removeActeur(Artiste $acteur): self
    {
        $this->acteurs->removeElement($acteur);

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

    public function getAfficheFile(){
        return $this->afficheFile;
    }

    public function setAfficheFile(File $file){
        $this->afficheFile = $file;

        if( $file !== null ){
            $this->maj= new \DateTimeImmutable();
        }

        return $this;
    }
}
