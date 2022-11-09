<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    private $username;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $lastname;

    #[ORM\ManyToMany(targetEntity: Studio::class, mappedBy: 'subscriber')]
    private $subscriber;

    #[ORM\ManyToOne(targetEntity: Publisher::class, inversedBy: 'users')]
    private $publisher;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Review::class, orphanRemoval: true)]
    private $reviews;

    #[ORM\OneToMany(mappedBy: 'admin', targetEntity: Review::class)]
    private $checkedReviews;

    #[ORM\Column(type: 'date', nullable: true)]
    private $date;

    public function __construct()
    {
        $this->subscriber = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->checkedReviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString() {
        return $this->email;
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
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return Collection<int, Studio>
     */
    public function getSubscriber(): Collection
    {
        return $this->subscriber;
    }

    public function addSubscriber(Studio $subscriber): self
    {
        if (!$this->subscriber->contains($subscriber)) {
            $this->subscriber[] = $subscriber;
            $subscriber->addSubscriber($this);
        }

        return $this;
    }

    public function removeSubscriber(Studio $subscriber): self
    {
        if ($this->subscriber->removeElement($subscriber)) {
            $subscriber->removeSubscriber($this);
        }

        return $this;
    }

    public function getPublisher(): ?Publisher
    {
        return $this->publisher;
    }

    public function setPublisher(?Publisher $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setAuthor($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getAuthor() === $this) {
                $review->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getCheckedReviews(): Collection
    {
        return $this->checkedReviews;
    }

    public function addCheckedReview(Review $checkedReview): self
    {
        if (!$this->checkedReviews->contains($checkedReview)) {
            $this->checkedReviews[] = $checkedReview;
            $checkedReview->setAdmin($this);
        }

        return $this;
    }

    public function removeCheckedReview(Review $checkedReview): self
    {
        if ($this->checkedReviews->removeElement($checkedReview)) {
            // set the owning side to null (unless already changed)
            if ($checkedReview->getAdmin() === $this) {
                $checkedReview->setAdmin(null);
            }
        }

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
