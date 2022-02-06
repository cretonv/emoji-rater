<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ApiResource(
    collectionOperations: [
        "get",
        "post" => ["security_post_denormalize" => "is_granted('READ_WA_ITEM', object)"]
    ],
    itemOperations: [
        "get" => ["security" => "is_granted('READ_WA_ITEM', object)"],
        "put" => ["security" => "is_granted('READ_WA_ITEM', object)"],
        "delete" => ["security" => "is_granted('READ_WA_ITEM', object)"],
    ],
    normalizationContext: [
        "groups" => ['product']
    ]
)]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product implements WebsiteAwareInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $reference;

    #[ORM\ManyToOne(targetEntity: Website::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $website;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Rating::class)]
    private $ratings;

    public function __construct() {
        $this->ratings = new ArrayCollection();
    }

    #[Groups("product")]
    public function getId(): ?int {
        return $this->id;
    }

    #[Groups("product")]
    public function getReference(): ?string {
        return $this->reference;
    }

    public function setReference(string $reference): self {
        $this->reference = $reference;

        return $this;
    }

    public function getWebsite(): ?Website {
        return $this->website;
    }

    public function setWebsite(?Website $website): self {
        $this->website = $website;

        return $this;
    }

    /**
     * @return Collection|Rating[]
     */
    #[Groups("product")]
    public function getRatings(): Collection {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setProduct($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getProduct() === $this) {
                $rating->setProduct(null);
            }
        }

        return $this;
    }


    #[Groups("product")]
    public function getAverageMark() {
        $ratings = $this->getRatings()->toArray();
        $c = count($ratings);

        return array_reduce($ratings, function($average, $rating) use ($c) {
            /** @var Rating $rating */
            return $average + ($rating->getMark() / $c);
        }, 0);
    }
}
