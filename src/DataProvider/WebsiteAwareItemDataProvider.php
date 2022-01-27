<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Context\WebsiteContext;
use App\Entity\WebsiteAwareInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class WebsiteAwareItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface {

    public function __construct(
        private ItemDataProviderInterface $decorated,
        private WebsiteContext $websiteContext
    ) {
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []) {
        die('coucou');
        $item = $this->decorated->getItem($resourceClass, $id, $operationName, $context);
        if ($item instanceof WebsiteAwareInterface) {
            $website = $this->websiteContext->getWebsite();
            if ($website->getId() !== $item->getWebsite()->getId()) {
                throw new UnauthorizedHttpException("You don't have access to this ressource.");
            }
        }
        return $item;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool {
        return in_array(WebsiteAwareInterface::class, class_implements($resourceClass));
    }
}
