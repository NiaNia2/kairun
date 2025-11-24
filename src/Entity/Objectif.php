<?php

namespace App\Entity;

use App\Repository\ObjectifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ObjectifRepository::class)]
class Objectif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * @var Collection<int, recette>
     */
    #[ORM\OneToMany(targetEntity: recette::class, mappedBy: 'objectif')]
    private Collection $recette;

    public function __construct()
    {
        $this->recette = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, recette>
     */
    public function getRecette(): Collection
    {
        return $this->recette;
    }

    public function addRecette(recette $recette): static
    {
        if (!$this->recette->contains($recette)) {
            $this->recette->add($recette);
            $recette->setObjectif($this);
        }

        return $this;
    }

    public function removeRecette(recette $recette): static
    {
        if ($this->recette->removeElement($recette)) {
            // set the owning side to null (unless already changed)
            if ($recette->getObjectif() === $this) {
                $recette->setObjectif(null);
            }
        }

        return $this;
    }
}
