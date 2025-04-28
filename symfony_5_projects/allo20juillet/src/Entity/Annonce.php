<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;



/**
 * @ORM\Entity(repositoryClass=AnnonceRepository::class)
 
 * @Vich\Uploadable
 */
class Annonce
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $experience;



    /**
     * @ORM\Column(type="boolean")
     */
    private $isInYourHome;


    /**
     * @ORM\Column(type="boolean")
     */
    private $isInClientHome;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isInByVisio;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @Vich\UploadableField( mapping="photos", fileNameProperty="image")
     * 
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cv;

    /**
     * @Vich\UploadableField( mapping="cvs", fileNameProperty="cv")
     * 
     */
    private $cvFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rib;


    /**
     * @Vich\UploadableField( mapping="ribs", fileNameProperty="rib")
     * 
     */
    private $ribFile;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $siretnumber;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $maj;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active = 0;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="annonce", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Departement::class, inversedBy="annonce")
     * @ORM\JoinColumn(nullable=false)
     */
    private $departement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nickname;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $coachingPlaces;

    /**
     * @ORM\OneToMany(targetEntity=Calendar::class, mappedBy="annonce")
     */
    private $calendars;

    /**
     * @ORM\ManyToMany(targetEntity=Places::class, mappedBy="annonce")
     */
    private $places;

    /**
     * @ORM\Column(type="text")
     * @ORM\JoinColumn(nullable=true)
     */
    private $formation;

    public function __construct()
    {
        $this->calendars = new ArrayCollection();
        $this->places = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(string $experience): self
    {
        $this->experience = $experience;

        return $this;
    }



    public function getIsInYourHome(): ?bool
    {
        return $this->isInYourHome;
    }

    public function setIsInYourHome(bool $isInYourHome): self
    {
        $this->isInYourHome = $isInYourHome;

        return $this;
    }

    public function getIsInClientHome(): ?bool
    {
        return $this->isInClientHome;
    }

    public function setIsInClientHome(bool $isInClientHome): self
    {
        $this->isInClientHome = $isInClientHome;

        return $this;
    }

    public function getIsInByVisio(): ?bool
    {
        return $this->isInByVisio;
    }

    public function setIsInByVisio(bool $isInByVisio): self
    {
        $this->isInByVisio = $isInByVisio;

        return $this;
    }




    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile(File $imageFile)
    {
        $this->imageFile = $imageFile;

        if ($imageFile !== null) {
            $this->maj = new \DateTimeImmutable();
        }
        return $this;
    }





    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getCvFile()
    {
        return $this->cvFile;
    }

    public function setCvFile(File $cvFile)
    {
        $this->cvFile = $cvFile;

        if ($cvFile !== null) {
            $this->maj = new \DateTimeImmutable();
        }
        return $this;
    }

    public function getRib(): ?string
    {
        return $this->rib;
    }

    public function setRib(?string $rib): self
    {
        $this->rib = $rib;

        return $this;
    }

    public function getRibFile()
    {
        return $this->ribFile;
    }

    public function setRibFile(File $ribFile)
    {
        $this->ribFile = $ribFile;

        if ($ribFile !== null) {
            $this->maj = new \DateTimeImmutable();
        }
        return $this;
    }

    public function getSiretnumber(): ?string
    {
        return $this->siretnumber;
    }

    public function setSiretnumber(?string $siretnumber): self
    {
        $this->siretnumber = $siretnumber;

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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    //indiquer la manière dont les informations seront recupérées 
    public function __toString()
    {

        $result = $this->nickname . "[spr]";
        if ($this->getIsInYourHome()) {
            $result = $this->isInYourHome . "[spr]";
            $result .= $this->description . "[spr]";
        }

        return $result;
    }

    public function getCoachingPlaces(): ?string
    {
        return $this->coachingPlaces;
    }

    public function setCoachingPlaces(?string $coachingPlaces): self
    {
        $this->coachingPlaces = $coachingPlaces;

        return $this;
    }

    /**
     * @return Collection|Calendar[]
     */
    public function getCalendars(): Collection
    {
        return $this->calendars;
    }

    public function addCalendar(Calendar $calendar): self
    {
        if (!$this->calendars->contains($calendar)) {
            $this->calendars[] = $calendar;
            $calendar->setAnnonce($this);
        }

        return $this;
    }

    public function removeCalendar(Calendar $calendar): self
    {
        if ($this->calendars->removeElement($calendar)) {
            // set the owning side to null (unless already changed)
            if ($calendar->getAnnonce() === $this) {
                $calendar->setAnnonce(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Places[]
     */
    public function getPlaces(): Collection
    {
        return $this->places;
    }

    public function addPlace(Places $place): self
    {
        if (!$this->places->contains($place)) {
            $this->places[] = $place;
            $place->addAnnonce($this);
        }

        return $this;
    }

    public function removePlace(Places $place): self
    {
        if ($this->places->removeElement($place)) {
            $place->removeAnnonce($this);
        }

        return $this;
    }

    public function getFormation(): ?string
    {
        return $this->formation;
    }

    public function setFormation(string $formation): self
    {
        $this->formation = $formation;

        return $this;
    }
}
