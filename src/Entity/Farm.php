<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use App\Repository\FarmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FarmRepository::class)]
#[
    ApiResource,
    ApiFilter(
        SearchFilter::class,
        properties: [
            'name' => SearchFilter::STRATEGY_PARTIAL,
            'farmOwner' => SearchFilter::STRATEGY_PARTIAL,
            'animals'
        ]
    ),
    Apifilter(
        OrderFilter::class,
        properties: [
            'animals.birth'
        ]
    ),
    ApiFilter(
        PropertyFilter::class
    )
]
class Farm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    #[Groups(['animal.read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['animal.read'])]
    private ?string $Name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['animal.read'])]
    private ?string $farmOwner = null;

    #[ORM\OneToMany(mappedBy: 'farm', targetEntity: Animal::class, orphanRemoval: true)]
    private Collection $animals;

    #[Groups('animal.read')]
    private $animalCount = 0;

    public function __construct()
    {
        $this->animals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getFarmOwner(): ?string
    {
        return $this->farmOwner;
    }

    public function setFarmOwner(string $farmOwner): self
    {
        $this->farmOwner = $farmOwner;

        return $this;
    }

    /**
     * @return Collection<int, Animal>
     */
    public function getAnimals(): Collection
    {
        return $this->animals;
    }

    public function addAnimal(Animal $animal): self
    {
        if (!$this->animals->contains($animal)) {
            $this->animals->add($animal);
            $animal->setFarm($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): self
    {
        if ($this->animals->removeElement($animal)) {
            // set the owning side to null (unless already changed)
            if ($animal->getFarm() === $this) {
                $animal->setFarm(null);
            }
        }

        return $this;
    }

    public function setAnimalsCount()
    {
        $this->animalCount = $this->animals->count();
    }

    public function getAnimalsCount(): ?int
    {
        $this->setAnimalsCount();
        return $this->animalCount;
    }
}
