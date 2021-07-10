<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PrestationsRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PrestationsRepository::class)
 * @ApiResource(
 *  normalizationContext={"groups"={"read:presta"}},
 *  itemOperations={
 *      "get"={},
 *  },
 *  collectionOperations={
 *       "get"={},
 *       "get_prestations"={
 *          "method"="GET",
 *          "path"="/prestations/get/{id}",
 *          "controller"=App\Controller\Api\GetPrestations::class
 *       }
 *  }
 * )
 */
class Prestations
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:presta"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:presta"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:presta"})
     */
    private $image;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read:presta"})
     */
    private $prestaTime;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="prestation")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="prestations")
     * @Groups({"read:presta"})
     */
    private $category;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPrestaTime(): ?int
    {
        return $this->prestaTime;
    }

    public function setPrestaTime(int $prestaTime): self
    {
        $this->prestaTime = $prestaTime;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function getCategory(): ?Categories
    {
        return $this->category;
    }

    public function setCategory(?Categories $category): self
    {
        $this->category = $category;

        return $this;
    }
    
}
