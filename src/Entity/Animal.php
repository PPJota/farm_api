<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use App\Repository\AnimalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
#[
    ApiResource(
        normalizationContext: ['groups' => ['animal.read']],
        denormalizationContext: ['groups' => ['animal.write']],
        paginationItemsPerPage: 10
    ),
    ApiFilter(
        SearchFilter::class,
        properties: [
            'specie',
            'farm',
            'specie.name',
            'farm.name'
        ]
    ),
    ApiFilter(
        BooleanFilter::class,
        properties: [
            'isSick'
        ]
    ),
    ApiFilter(
        DateFilter::class,
        properties: [
            'birth',
            'death' => DateFilter::INCLUDE_NULL_BEFORE_AND_AFTER
        ]
    ),
    ApiFilter(
        PropertyFilter::class
    )
]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['animal.read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['animal.read', 'animal.write'])]
    private ?Specie $specie = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['animal.read', 'animal.write'])]
    private ?Farm $farm = null;

    #[ORM\Column]
    #[Groups(['animal.read', 'animal.write'])]
    private ?bool $isSick = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['animal.read', 'animal.write'])]
    private ?\DateTimeInterface $birth = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['animal.read', 'animal.write'])]
    private ?\DateTimeInterface $death = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpecie(): ?Specie
    {
        return $this->specie;
    }

    public function setSpecie(?Specie $specie): self
    {
        $this->specie = $specie;

        return $this;
    }

    public function getFarm(): ?Farm
    {
        return $this->farm;
    }

    public function setFarm(?Farm $farm): self
    {
        $this->farm = $farm;

        return $this;
    }

    public function isIsSick(): ?bool
    {
        return $this->isSick;
    }

    public function setIsSick(bool $isSick): self
    {
        $this->isSick = $isSick;

        return $this;
    }

    public function getBirth(): ?\DateTimeInterface
    {
        return $this->birth;
    }

    public function setBirth(\DateTimeInterface $birth): self
    {
        $this->birth = $birth;

        return $this;
    }

    public function getDeath(): ?\DateTimeInterface
    {
        return $this->death;
    }

    public function setDeath(?\DateTimeInterface $death): self
    {
        $this->death = $death;

        return $this;
    }
}
