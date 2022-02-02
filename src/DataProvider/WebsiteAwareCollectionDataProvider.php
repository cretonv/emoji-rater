<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Context\WebsiteContext;
use App\Entity\Product;
use App\Entity\Rating;
use App\Entity\WebsiteAwareInterface;
use App\Repository\RatingRepository;

final class WebsiteAwareCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{

    public function __construct(
//        private ContextAwareCollectionDataProviderInterface $decorated,
    private RatingRepository $ratingRepository,
        private WebsiteContext $websiteContext
    ) {
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []) {
        if ($resourceClass === Product::class) {
            return $this->getProductsCollection($context);
        }
        if ($resourceClass === Rating::class) {
            return $this->getRatingsCollection($context);
        }
//        return $this->decorated->getCollection($resourceClass, $operationName, $context);
    }

    private function getProductsCollection($context): \Doctrine\Common\Collections\Collection|array {
        return $this->websiteContext->getWebsite()->getProducts();
    }

    private function getRatingsCollection($context): \Doctrine\Common\Collections\Collection|array {
        return $this->ratingRepository->findByWebsite($this->websiteContext->getWebsite());
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool {
        $interfaces = class_implements($resourceClass);
        return $interfaces && in_array(WebsiteAwareInterface::class, $interfaces);
    }
}
