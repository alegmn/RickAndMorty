<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use ApiPlatform\Core\Annotation\ApiResource;


/**
 * @ORM\Entity(repositoryClass=CharacterRepository::class)
 * @Serializer\XmlRoot("character")
 *
 * @Hateoas\Relation("self", href = "expr('/api/characters/' ~ object.getId())")
 */
class Character
{
    /**
     * @Serializer\XmlAttribute
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isProtagonist;

    /**
     * @ORM\Column(type="string")
     */
    private $occupation;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $gender;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getIsProtagonist(): ?bool
    {
        return $this->isProtagonist;
    }

    public function setIsProtagonist(bool $isProtagonist): self
    {
        $this->isProtagonist = $isProtagonist;

        return $this;
    }

    public function getOccupation(): ?string
    {
        return $this->occupation;
    }

    public function setOccupation(string $occupation): self
    {
        $this->occupation = $occupation;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getFullName(): ?string
    {
        return "$this->name $this->lastName";
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('lastName', new Assert\NotBlank());
        $metadata->addPropertyConstraint('gender', new Assert\NotBlank());
        $metadata->addPropertyConstraint('isProtagonist', new Assert\NotBlank());
        $metadata->addPropertyConstraint('occupation', new Assert\NotBlank());
        $metadata->addPropertyConstraint(
            'gender',
            new Assert\Choice(['male', 'female'])
        );
    }
}
