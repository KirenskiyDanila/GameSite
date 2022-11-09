<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $genre;

    #[ORM\Column(type: 'date')]
    private $announceDate;

    #[ORM\Column(type: 'date', nullable: true)]
    private $releaseDate;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\ManyToOne(targetEntity: Studio::class, inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private $studio;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Price::class, orphanRemoval: true)]
    private $prices;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Review::class, orphanRemoval: true)]
    private $reviews;

    #[ORM\ManyToOne(targetEntity: Publisher::class, inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private $publisher;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $photo;

    public function __construct()
    {
        $this->prices = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString() {
        if ($this->releaseDate != null) {
            return $this->name . " (" . $this->releaseDate->format('Y') . ")";
        }
        else {
            return $this->name . " (Нет даты выхода)";
        }
    }

    public function getAveragePrice(): int
    {
        $total = 0;
        $count = 0;
        foreach ($this->getPrices() as $price) {
            $total += $price->getCost();
            $count++;
        }
        if ($count != 0) {
            return $total / $count;
        }
        else {
            return 0;
        }
    }

    public function getAverageGrade(): float
    {

        $total = 0.0;
        $count = 0;
        foreach ($this->getReviews() as $review) {
            if (!$review->IsApproved()) {
                continue;
            }
            $total += $review->getGrade();
            $count++;
        }
        if ($count != 0) {
            return round($total / $count, 2);
        }
        else {
            return 0;
        }
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

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getAnnounceDate(): ?\DateTimeInterface
    {
        return $this->announceDate;
    }

    public function setAnnounceDate(\DateTimeInterface $announceDate): self
    {
        $this->announceDate = $announceDate;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStudio(): ?Studio
    {
        return $this->studio;
    }

    public function setStudio(?Studio $studio): self
    {
        $this->studio = $studio;

        return $this;
    }

    /**
     * @return Collection<int, Price>
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function addPrice(Price $price): self
    {
        if (!$this->prices->contains($price)) {
            $this->prices[] = $price;
            $price->setGame($this);
        }

        return $this;
    }

    public function removePrice(Price $price): self
    {
        if ($this->prices->removeElement($price)) {
            // set the owning side to null (unless already changed)
            if ($price->getGame() === $this) {
                $price->setGame(null);
            }
        }

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
            $review->setGame($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getGame() === $this) {
                $review->setGame(null);
            }
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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }
}
