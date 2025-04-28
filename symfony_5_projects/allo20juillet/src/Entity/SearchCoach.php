<?php

namespace App\Entity;




class SearchCoach
{

    /**
     * @var Region []
     */
    private $region = [];

    /**
     * @var Departement []
     */
    private $departement = [];

    /**
     * @var Places[]
     */
    private $places = [];


    private $city;



    public function getRegion(): ?array
    {
        return $this->region;
    }

    public function setRegion(?array $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getDepartement(): ?array
    {
        return $this->departement;
    }

    public function setDepartement(?array $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public function getPlaces(): ?array
    {
        return $this->places;
    }

    public function setPlaces(?array $places): self
    {
        $this->places = $places;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }
}
