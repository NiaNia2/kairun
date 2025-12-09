<?php

namespace App\Entity;

use App\Entity\User;
use Vich\Uploadable;
use DateTimeImmutable;
use App\Entity\Objectif;
use App\Entity\Ingredients;
use App\Entity\TypeDeRepas;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
// use Vich\UploaderBundle\Entity\File;
use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\Collection;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: RecetteRepository::class)]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $temp_prepa_min = null;

    #[ORM\Column]
    private ?int $temps_cuissons_min = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $cree_le = null;

    #[ORM\ManyToOne(inversedBy: 'Recette')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[Vich\UploadableField(mapping: 'images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?File $file = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Ingredients>
     */
    #[ORM\ManyToMany(targetEntity: ingredients::class, inversedBy: 'recettes')]
    private Collection $ingredient;

    #[ORM\ManyToOne(inversedBy: 'recette')]
    private ?Objectif $objectif = null;

    #[ORM\ManyToOne(inversedBy: 'recettes')]
    private ?TypeDeRepas $typeDeRepas = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function __construct()
    {
        $this->ingredient = new ArrayCollection();
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

    public function getTempPrepaMin(): ?int
    {
        return $this->temp_prepa_min;
    }

    public function setTempPrepaMin(int $temp_prepa_min): static
    {
        $this->temp_prepa_min = $temp_prepa_min;

        return $this;
    }

    public function getTempsCuissonsMin(): ?int
    {
        return $this->temps_cuissons_min;
    }

    public function setTempsCuissonsMin(int $temps_cuissons_min): static
    {
        $this->temps_cuissons_min = $temps_cuissons_min;

        return $this;
    }

    public function getCreeLe(): ?\DateTimeImmutable
    {
        return $this->cree_le;
    }

    public function setCreeLe(\DateTimeImmutable $cree_le): static
    {
        $this->cree_le = $cree_le;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if ($imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {

        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {

        return $this->imageName;
    }
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }



    /**
     * @return Collection<int, Ingredients>
     */
    public function getIngredient(): Collection
    {
        return $this->ingredient;
    }

    public function addIngredient(ingredients $ingredient): static
    {
        if (!$this->ingredient->contains($ingredient)) {
            $this->ingredient->add($ingredient);
        }

        return $this;
    }

    public function removeIngredient(ingredients $ingredient): static
    {
        $this->ingredient->removeElement($ingredient);

        return $this;
    }

    public function getObjectif(): ?Objectif
    {
        return $this->objectif;
    }

    public function setObjectif(?Objectif $objectif): static
    {
        $this->objectif = $objectif;

        return $this;
    }

    public function getTypeDeRepas(): ?TypeDeRepas
    {
        return $this->typeDeRepas;
    }

    public function setTypeDeRepas(?TypeDeRepas $typeDeRepas): static
    {
        $this->typeDeRepas = $typeDeRepas;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
