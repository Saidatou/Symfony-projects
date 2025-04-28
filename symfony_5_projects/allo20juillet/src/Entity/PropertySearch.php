<?php

namespace App\Entity;

class PropertySearch
{
    private $city;
    public function getCity(): ?string
    {
        return $this->city;
    }
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }
}
