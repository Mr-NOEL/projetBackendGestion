<?php

namespace App\Entity;

use App\Repository\VetementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VetementRepository::class)
 */
class Vetement
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
    private $descriptif;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $prixDeBase;

    /**
     * @ORM\Column(type="integer")
     */
    private $taille;

    /**
     * @ORM\Column(type="date")
     */
    private $dateAchat;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="vetements")
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    public function getPrixDeBase(): ?string
    {
        return $this->prixDeBase;
    }

    public function setPrixDeBase(string $prixDeBase): self
    {
        $this->prixDeBase = $prixDeBase;

        return $this;
    }

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(string $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->dateAchat;
    }

    public function setDateAchat(\DateTimeInterface $dateAchat): self
    {
        $this->dateAchat = $dateAchat;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }
}
