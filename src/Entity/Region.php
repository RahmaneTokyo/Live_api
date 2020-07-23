<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RegionRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RegionRepository::class)
 */
class Region
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"listRegionSimple"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listRegionSimple"})
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listRegionSimple"})
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Departement::class, mappedBy="region")
     * @Groups({"listRegionSimple"})
     */
    private $departement;

    public function __construct()
    {
        $this->departement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
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

    /**
     * @return Collection|Departement[]
     */
    public function getDepartement(): Collection
    {
        return $this->departement;
    }

    public function addDepartement(Departement $departement): self
    {
        if (!$this->departement->contains($departement)) {
            $this->departement[] = $departement;
            $departement->setRegion($this);
        }

        return $this;
    }

    public function removeDepartement(Departement $departement): self
    {
        if ($this->departement->contains($departement)) {
            $this->departement->removeElement($departement);
            // set the owning side to null (unless already changed)
            if ($departement->getRegion() === $this) {
                $departement->setRegion(null);
            }
        }

        return $this;
    }
}
