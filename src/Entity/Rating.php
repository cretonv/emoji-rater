<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RatingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'rating:collection:get']],
        "post" => [ "security_post_denormalize" => "is_granted('READ_WA_ITEM', object)" ]
    ],
    itemOperations: [
        "get" => [ "security" => "is_granted('READ_WA_ITEM', object)" ],
    ]
)]
#[ORM\Entity(repositoryClass: RatingRepository::class)]
class Rating implements WebsiteAwareInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'float')]
    private $mark;

    #[ORM\Column(type: 'string', length: 255)]
    private $authorUserMail;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'ratings')]
    #[ORM\JoinColumn(nullable: false)]
    private $product;

    #[ORM\Column(type: 'json', nullable: true)]
    private $metadata = [];

    #[ORM\OneToMany(mappedBy: 'rating', targetEntity: Vote::class)]
    private $votes;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMark(): ?float
    {
        return $this->mark;
    }

    public function setMark(float $mark): self
    {
        $this->mark = $mark;

        return $this;
    }

    public function getAuthorUserMail(): ?string
    {
        return $this->authorUserMail;
    }

    public function setAuthorUserMail(string $authorUserMail): self
    {
        $this->authorUserMail = $authorUserMail;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @return Collection|Vote[]
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setRating($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getRating() === $this) {
                $vote->setRating(null);
            }
        }

        return $this;
    }

    public function getWebsite(): ?Website {
        return $this->getProduct()->getWebsite();
    }

    #[Groups("rating:collection:get")] // <- MAGIC IS HERE, you can set a group on a method.
    public function getAverage(): int
    {
        return 5;
    }
}
