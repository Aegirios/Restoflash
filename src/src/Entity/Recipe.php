<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Ingrediant::class, inversedBy: 'recipes')]
    private Collection $Ingrediants;

    #[ORM\Column]
    private ?int $difficulty = null;

    #[ORM\ManyToMany(targetEntity: Menu::class, mappedBy: 'recipes')]
    private Collection $menus;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'recipes')]
    private Collection $Recipes;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'Recipes')]
    private Collection $recipes;

    public function __construct()
    {
        $this->Ingrediants = new ArrayCollection();
        $this->menus = new ArrayCollection();
        $this->Recipes = new ArrayCollection();
        $this->recipes = new ArrayCollection();
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
     * @return Collection<int, Ingrediant>
     */
    public function getIngrediants(): Collection
    {
        return $this->Ingrediants;
    }

    public function addIngrediant(Ingrediant $ingrediant): static
    {
        if (!$this->Ingrediants->contains($ingrediant)) {
            $this->Ingrediants->add($ingrediant);
        }

        return $this;
    }

    public function removeIngrediant(Ingrediant $ingrediant): static
    {
        $this->Ingrediants->removeElement($ingrediant);

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): static
    {
        if (!$this->menus->contains($menu)) {
            $this->menus->add($menu);
            $menu->addRecipe($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): static
    {
        if ($this->menus->removeElement($menu)) {
            $menu->removeRecipe($this);
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<int, self>
     */
    public function getRecipes(): Collection
    {
        return $this->Recipes;
    }

    public function addRecipe(self $recipe): static
    {
        if (!$this->Recipes->contains($recipe)) {
            $this->Recipes->add($recipe);
        }

        return $this;
    }

    public function removeRecipe(self $recipe): static
    {
        $this->Recipes->removeElement($recipe);

        return $this;
    }
}
