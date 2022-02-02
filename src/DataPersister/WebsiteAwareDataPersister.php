<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Context\WebsiteContext;
use App\Entity\Product;
use App\Entity\Rating;
use App\Entity\WebsiteAwareInterface;

/**
 * Un seul data persister pour tout, c'est un antipattern, mais symfony
 * n'appelait jamais les méthodes support des autres quand il y en avait
 * plusieurs donc à un moment faut bien que ça fonctionne
 */
final class WebsiteAwareDataPersister implements ContextAwareDataPersisterInterface {

    public function __construct(
        private ContextAwareDataPersisterInterface $decorated,
        private WebsiteContext $websiteContext
    ) {
    }

    public function supports($data, array $context = []): bool {
        return $data instanceof WebsiteAwareInterface;
    }

    public function persist($data, array $context = []) {

        if ($data instanceof Product) {
            return $this->persistProduct($data, $context);
        }
        if ($data instanceof Rating) {
            return $this->persistRating($data, $context);
        }
    }

    private function persistProduct(Product $product, $context) {
        $website = $this->websiteContext->getWebsite();
        $product->setWebsite($website);
        return $this->decorated->persist($product, $context);
    }

    private function persistRating(Rating $rating, $context) {
//        dd($rating);
        return $this->decorated->persist($rating, $context);
    }

    public function remove($data, array $context = []) {

        return $this->decorated->remove($data, $context);
    }
}
