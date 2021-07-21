<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UsersRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @ApiResource()
 */
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath = "password", message="Votre mot de passe doit être identique à celui entré ci-dessus")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $companyName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $timeLapse;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $workDays;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $showPreta;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $subscriberUser;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $employeColor;

    /**
     * @ORM\OneToMany(targetEntity=Categories::class, mappedBy="users")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Users::class, mappedBy="patron")
     */
    private $employes;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="employes")
     */
    private $patron;

    /**
     * @ORM\OneToMany(targetEntity=Appointments::class, mappedBy="users")
     */
    private $appointement;

    /**
     * @ORM\OneToMany(targetEntity=Prestations::class, mappedBy="users")
     */
    private $prestation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $employeesNbr;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->appointement = new ArrayCollection();
        $this->prestation = new ArrayCollection();
        $this->employes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

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

    public function getTimeLapse(): ?string
    {
        return $this->timeLapse;
    }

    public function setTimeLapse(string $timeLapse): self
    {
        $this->timeLapse = $timeLapse;

        return $this;
    }

    public function getShowPreta()
    {
        return $this->showPreta;
    }

    public function setShowPreta($showPreta): self
    {
        $this->showPreta = $showPreta;

        return $this;
    }

    public function getSubscriberUser()
    {
        return $this->subscriberUser;
    }

    public function setSubscriberUser($subscriberUser): self
    {
        $this->subscriberUser = $subscriberUser;

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
            $category->setUsers($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): self
    {
        if ($this->category->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getUsers() === $this) {
                $category->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Appointments[]
     */
    public function getAppointement(): Collection
    {
        return $this->appointement;
    }

    public function addAppointement(Appointments $appointement): self
    {
        if (!$this->appointement->contains($appointement)) {
            $this->appointement[] = $appointement;
            $appointement->setUsers($this);
        }

        return $this;
    }

    public function removeAppointement(Appointments $appointement): self
    {
        if ($this->appointement->removeElement($appointement)) {
            // set the owning side to null (unless already changed)
            if ($appointement->getUsers() === $this) {
                $appointement->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Prestations[]
     */
    public function getPrestation(): Collection
    {
        return $this->prestation;
    }

    public function addPrestation(Prestations $prestation): self
    {
        if (!$this->prestation->contains($prestation)) {
            $this->prestation[] = $prestation;
            $prestation->setUsers($this);
        }

        return $this;
    }

    public function removePrestation(Prestations $prestation): self
    {
        if ($this->prestation->removeElement($prestation)) {
            // set the owning side to null (unless already changed)
            if ($prestation->getUsers() === $this) {
                $prestation->setUsers(null);
            }
        }

        return $this;
    }

    public function getEmployeesNbr(): ?int
    {
        return $this->employeesNbr;
    }

    public function setEmployeesNbr(?int $employeesNbr): self
    {
        $this->employeesNbr = $employeesNbr;

        return $this;
    }

    public function getWorkDays(): ?array
    {
        return $this->workDays;
    }

    public function setWorkDays(?array $workDays): self
    {
        $this->workDays = $workDays;

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getEmployes(): Collection
    {
        return $this->employes;
    }

    public function addEmploye(Users $employe): self
    {
        if (!$this->employes->contains($employe)) {
            $this->employes[] = $employe;
            $employe->setPatron($this);
        }

        return $this;
    }

    public function removeEmploye(Users $employe): self
    {
        if ($this->employes->removeElement($employe)) {
            // set the owning side to null (unless already changed)
            if ($employe->getPatron() === $this) {
                $employe->setPatron(null);
            }
        }

        return $this;
    }

    public function getPatron(): ?self
    {
        return $this->patron;
    }

    public function setPatron(?self $patron): self
    {
        $this->patron = $patron;

        return $this;
    }

    public function getEmployeColor(): ?string
    {
        return $this->employeColor;
    }

    public function setEmployeColor(?string $employeColor): self
    {
        $this->employeColor = $employeColor;

        return $this;
    }
}
