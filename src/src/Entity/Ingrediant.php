<?php

namespace App\Entity;

use App\Repository\IngrediantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngrediantRepository::class)]
class Ingrediant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Recipe::class, mappedBy: 'Ingrediants')]
    private Collection $recipes;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Stock::class)]
    private Collection $stocks;

    #[ORM\OneToMany(mappedBy: 'Ingrediant', targetEntity: Stock::class)]
    private Collection $stock;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
        $this->stocks = new ArrayCollection();
        $this->stock = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): static
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->addIngrediant($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): static
    {
        if ($this->recipes->removeElement($recipe)) {
            $recipe->removeIngrediant($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<int, Stock>
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stock $stock): static
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks->add($stock);
        }

        return $this;
    }

    public function removeStock(Stock $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            // set th owning side to null (unless already changed)
        }

        return $this;
    }

    /**
     * @return Collection<int, Stock>
     */
    public function getStock(): Collection
    {
        return $this->stock;
    }

}
