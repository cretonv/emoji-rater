<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Context\WebsiteContext;
use App\Entity\Product;
use App\Entity\WebsiteAwareInterface;
use App\Repository\ProductRepository;
use App\Repository\WebsiteRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

final class WebsiteAwareCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{

    public function __construct(
        private WebsiteContext $websiteContext
    ) {
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []) {
        if ($resourceClass === Product::class) {
            return $this->getProductsCollection();
        }
    }

    private function getProductsCollection(): \Doctrine\Common\Collections\Collection|array {
        return $this->websiteContext->getWebsite()->getProducts();
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool {
        $interfaces = class_implements($resourceClass);
        return $interfaces && in_array(WebsiteAwareInterface::class, $interfaces);
    }
}
