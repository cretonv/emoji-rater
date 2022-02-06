<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [
        "get",
        "post" => [ "security_post_denormalize" => "is_granted('READ_WA_ITEM', object)" ]
    ],
    itemOperations: [
        "get" => [ "security" => "is_granted('READ_WA_ITEM', object)" ],
        "put" => [ "security" => "is_granted('READ_WA_ITEM', object)" ],
        "delete" => [ "security" => "is_granted('READ_WA_ITEM', object)" ],
    ],
    normalizationContext: [
        "groups" => ['vote']
    ]
)]
#[ORM\Entity(repositoryClass: VoteRepository::class)]
class Vote implements WebsiteAwareInterface
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

    #[Groups("vote")]
    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups("vote")]
    public function getIsUp(): ?bool
    {
        return $this->isUp;
    }

    public function setIsUp(bool $isUp): self
    {
        $this->isUp = $isUp;

        return $this;
    }

    #[Groups("vote")]
    public function getAuthorUserEmail(): ?string
    {
        return $this->authorUserEmail;
    }

    public function setAuthorUserEmail(string $authorUserEmail): self
    {
        $this->authorUserEmail = $authorUserEmail;

        return $this;
    }

    #[Groups("vote")]
    public function getRating(): ?Rating
    {
        return $this->rating;
    }

    public function setRating(?Rating $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    #[Groups("vote")]
    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(?array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function getWebsite(): ?Website {
        return $this->getRating()->getWebsite();
    }
}
