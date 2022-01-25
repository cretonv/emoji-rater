<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: VoteRepository::class)]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'boolean')]
    private $isUp;

    #[ORM\Column(type: 'string', length: 255)]
    private $authorUserEmail;

    #[ORM\ManyToOne(targetEntity: Rating::class, inversedBy: 'votes')]
    #[ORM\JoinColumn(nullable: false)]
    private $rating;

    #[ORM\Column(type: 'json', nullable: true)]
    private $metadata = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsUp(): ?bool
    {
        return $this->isUp;
    }

    public function setIsUp(bool $isUp): self
    {
        $this->isUp = $isUp;

        return $this;
    }

    public function getAuthorUserEmail(): ?string
    {
        return $this->authorUserEmail;
    }

    public function setAuthorUserEmail(string $authorUserEmail): self
    {
        $this->authorUserEmail = $authorUserEmail;

        return $this;
    }

    public function getRating(): ?Rating
    {
        return $this->rating;
    }

    public function setRating(?Rating $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(?array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }
}
