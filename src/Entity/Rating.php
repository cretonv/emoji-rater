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
        "get" => [],
        "post" => [ "security_post_denormalize" => "is_granted('READ_WA_ITEM', object)" ]
    ],
    itemOperations: [
        "get" => [ "security" => "is_granted('READ_WA_ITEM', object)" ],
        "put" => [ "security" => "is_granted('READ_WA_ITEM', object)" ],
        "delete" => [ "security" => "is_granted('READ_WA_ITEM', object)" ],
    ],
    normalizationContext: [
        "groups" => ['rating']
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
    private $authorUserEmail;

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

    #[Groups("rating")]
    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups("rating")]
    public function getMark(): ?float
    {
        return $this->mark;
    }

    public function setMark(float $mark): self
    {
        $this->mark = $mark;

        return $this;
    }

    #[Groups("rating")]
    public function getAuthorUserEmail(): ?string
    {
        return $this->authorUserEmail;
    }

    public function setAuthorUserEmail(string $authorUserEmail): self
    {
        $this->authorUserEmail = $authorUserEmail;

        return $this;
    }

    #[Groups("rating")]
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    #[Groups("rating")]
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
    #[Groups("rating")]
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

    #[Groups("rating")]
    public function getUpVoteScore(): int
    {
        $votes = $this->getVotes()->toArray();
        return array_reduce($votes, function($val, $vote) {
            /** @var Vote $vote */
            if ($vote->getIsUp()) {
                return $val + 1;
            }
            return $val - 1;
        }, 0);
    }
}
