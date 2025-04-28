<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\HomeSliderRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=HomeSliderRepository::class)
 * @Vich\Uploadable
 */
class HomeSlider
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
    private $Title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $buttonMessage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $buttonURL;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @Vich\UploadableField( mapping="sliders", fileNameProperty="image")
     * 
     */
    private $imageFile;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isDisplayed;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $maj;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

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

    public function getButtonMessage(): ?string
    {
        return $this->buttonMessage;
    }

    public function setButtonMessage(string $buttonMessage): self
    {
        $this->buttonMessage = $buttonMessage;

        return $this;
    }

    public function getButtonURL(): ?string
    {
        return $this->buttonURL;
    }

    public function setButtonURL(string $buttonURL): self
    {
        $this->buttonURL = $buttonURL;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
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

    public function getIsDisplayed(): ?bool
    {
        return $this->isDisplayed;
    }

    public function setIsDisplayed(?bool $isDisplayed): self
    {
        $this->isDisplayed = $isDisplayed;

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
