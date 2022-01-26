<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Context\WebsiteContext;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\RequestStack;

final class ProductDataPersister implements ContextAwareDataPersisterInterface {

    public function __construct(
        private ContextAwareDataPersisterInterface $decorated,
        private WebsiteContext $websiteContext
    ) {
    }

    public function supports($data, array $context = []): bool {
        return $data instanceof Product;
    }

    public function persist($data, array $context = []) {
        $website = $this->websiteContext->getWebsite();

        if ($data instanceof Product) {
            $data->setWebsite($website);
        }

        //        if (
//            $data instanceof User && (
//                ($context['collection_operation_name'] ?? null) === 'post' ||
//                ($context['graphql_operation_name'] ?? null) === 'create'
//            )
//        ) {
//            $this->sendWelcomeEmail($data);
//        }

        return $this->decorated->persist($data, $context);
    }

    public function remove($data, array $context = []) {
        return $this->decorated->remove($data, $context);
    }
}
