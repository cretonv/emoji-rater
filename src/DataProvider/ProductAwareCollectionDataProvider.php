<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Context\WebsiteContext;
use App\Entity\WebsiteAwareInterface;


/**
 * Devra décorer la classe doctrine qui récupère la liste, et lever une exception si on essaie d'accéder à une data pas à nous
 */
final class ProductAwareCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface {

    public function __construct(
        private WebsiteContext $websiteContext
    ) {
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []) {
        $website = $this->websiteContext->getWebsite();

        return $website->getProducts();
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool {
        return in_array(WebsiteAwareInterface::class, class_implements($resourceClass));
    }
}
