<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Context\WebsiteContext;
use App\Entity\Product;
use App\Entity\Rating;
use App\Entity\Vote;
use App\Entity\WebsiteAwareInterface;
use App\Repository\ProductRepository;
use App\Repository\RatingRepository;
use App\Repository\VoteRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

final class WebsiteAwareCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{

    public function __construct(
//        private ContextAwareCollectionDataProviderInterface $decorated,
        private RatingRepository  $ratingRepository,
        private ProductRepository $productRepository,
        private VoteRepository    $voteRepository,
        private WebsiteContext    $websiteContext
    ) {
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []) {
        if ($resourceClass === Product::class) {
            return $this->getProductsCollection($context);
        }
        if ($resourceClass === Rating::class) {
            return $this->getRatingsCollection($context);
        }
        if ($resourceClass === Vote::class) {
            return $this->getVotesCollection($context);
        }
    }

    private function getProductsCollection($context): \Doctrine\Common\Collections\Collection|array {
        return $this->websiteContext->getWebsite()->getProducts();
    }

    private function getRatingsCollection($context): \Doctrine\Common\Collections\Collection|array {
        $website = $this->websiteContext->getWebsite();
        if (!empty($context['filters']['product'])) {
            $product = $this->productRepository->findOneBy([
                'website' => $website,
                'id' => $context['filters']['product'],
            ]);
            if (!$product) {
                throw new NotFoundHttpException("The product doesn't exist");
            }
            return $product->getRatings();
        }
        if (!empty($context['filters']['authorUserEmail'])) {
            return $this->ratingRepository->findByUserEmail($context['filters']['authorUserEmail']);
        }
        return $this->ratingRepository->findAllWebsite();
    }

    private function getVotesCollection($context): \Doctrine\Common\Collections\Collection|array {
        $website = $this->websiteContext->getWebsite();
        if (!empty($context['filters']['rating'])) {
            $rating = $this->ratingRepository->findOneBy([
                'id' => $context['filters']['rating'],
            ]);
            if (!$rating) {
                throw new NotFoundHttpException("The rating doesn't exist");
            }
            if ($rating->getWebsite() !== $website) {
                throw new UnauthorizedHttpException("You are not allowed to access this resource");
            }
            return $rating->getVotes();
        }
        if (!empty($context['filters']['authorUserEmail'])) {
            return $this->voteRepository->findByUserEmail($context['filters']['authorUserEmail']);
        }
        return $this->voteRepository->findAllWebsite();
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool {
        $interfaces = class_implements($resourceClass);
        return $interfaces && in_array(WebsiteAwareInterface::class, $interfaces);
    }
}
