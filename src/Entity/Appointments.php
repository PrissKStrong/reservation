<?php

namespace App\Entity;

use App\Entity\Prestations;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AppointmentsRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AppointmentsRepository::class)
 * @ApiResource(
 *  normalizationContext={"groups"={"read:appointments"}},
 *  itemOperations={
 *      "get"={},
 *      "put"={},
 *      "delete"={}
 *  },
 *  collectionOperations={
 *       "get"={},
 *       "post"={},
 *       "get_Appointments"={
 *          "method"="GET",
 *          "path"="/appointments/get/{id}",
 *          "controller"=App\Controller\Api\GetAppointments::class
 *       }
 *  }
 * )
 */
class Appointments
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:appointments"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read:appointments"})
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read:appointments"})
     */
    private $end;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:appointments"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:appointments"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:appointments"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:appointments"})
     */
    private $tel;

    /**
     * @ORM\ManyToOne(targetEntity=Prestations::class, inversedBy="appointement")
     * @Groups({"read:appointments"})
     */
    private $prestation;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="appointement")
     */
    private $users;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"read:appointments"})
     */
    private $note;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"read:appointments"})
     */
    private $groupeId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getPrestation(): ?Prestations
    {
        return $this->prestation;
    }

    public function setPrestation(?Prestations $prestation): self
    {
        $this->prestation = $prestation;

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

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getGroupeId(): ?int
    {
        return $this->groupeId;
    }

    public function setGroupeId(?int $groupeId): self
    {
        $this->groupeId = $groupeId;

        return $this;
    }


   
}
