<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeRepository::class)
 */
class Type
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
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Vetement::class, mappedBy="type")
     */
    private $vetements;

    public function __construct()
    {
        $this->vetements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Vetement[]
     */
    public function getVetements(): Collection
    {
        return $this->vetements;
    }

    public function addVetement(Vetement $vetement): self
    {
        if (!$this->vetements->contains($vetement)) {
            $this->vetements[] = $vetement;
            $vetement->setType($this);
        }

        return $this;
    }

    public function removeVetement(Vetement $vetement): self
    {
        if ($this->vetements->contains($vetement)) {
            $this->vetements->removeElement($vetement);
            // set the owning side to null (unless already changed)
            if ($vetement->getType() === $this) {
                $vetement->setType(null);
            }
        }

        return $this;
    }
}
