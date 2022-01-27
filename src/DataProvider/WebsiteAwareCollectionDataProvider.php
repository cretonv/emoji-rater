<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Context\WebsiteContext;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\WebsiteRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

final class WebsiteAwareCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface {

    public function __construct(
        private WebsiteContext $websiteContext
    ) {
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []) {
        $website = $this->websiteContext->getWebsite();

        return $website->getProducts();
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool {
        return Product::class === $resourceClass;
    }
}
