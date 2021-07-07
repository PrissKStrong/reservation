<?php

namespace App\Entity;

use App\Repository\PrestationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrestationsRepository::class)
 */
class Prestations
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="integer")
     */
    private $prestaTime;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="prestation")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Categories::class, mappedBy="prestations")
     */
    private $category;

    public function __construct()
    {
        $this->category = new ArrayCollection();
    }

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

    /**
     * @return Collection|Categories[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Categories $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
            $category->setPrestations($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): self
    {
        if ($this->category->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getPrestations() === $this) {
                $category->setPrestations(null);
            }
        }

        return $this;
    }

}
