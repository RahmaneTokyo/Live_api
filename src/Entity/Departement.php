<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DepartementRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DepartementRepository::class)
 */
class Departement
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
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="departement")
     * @ORM\JoinColumn(nullable=false)
     */
    private $region;

    /**
     * @ORM\OneToMany(targetEntity=Commune::class, mappedBy="departement")
     * @Groups({"listRegionSimple"})
     */
    private $commune;

    public function __construct()
    {
        $this->commune = new ArrayCollection();
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

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Collection|Commune[]
     */
    public function getCommune(): Collection
    {
        return $this->commune;
    }

    public function addCommune(Commune $commune): self
    {
        if (!$this->commune->contains($commune)) {
            $this->commune[] = $commune;
            $commune->setDepartement($this);
        }

        return $this;
    }

    public function removeCommune(Commune $commune): self
    {
        if ($this->commune->contains($commune)) {
            $this->commune->removeElement($commune);
            // set the owning side to null (unless already changed)
            if ($commune->getDepartement() === $this) {
                $commune->setDepartement(null);
            }
        }

        return $this;
    }
}
