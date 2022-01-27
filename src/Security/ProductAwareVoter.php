<?php

namespace App\Security;

use App\Context\WebsiteContext;
use App\Entity\Product;
use App\Entity\Website;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProductAwareVoter extends Voter {

    const READ_PRODUCT = 'READ_PRODUCT';

    public function __construct(
        private WebsiteContext $websiteContext
    ) {
    }

    protected function supports(string $attribute, $subject) {
        if (!in_array($attribute, [self::READ_PRODUCT])) {
            return false;
        }
        if (!$subject instanceof Product) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token) {
        $website = $this->websiteContext->getWebsite();
        /** @var Product $product */
        $product = $subject;
        switch ($attribute) {
            case self::READ_PRODUCT:
                return $this->canView($product, $website);
        }

        throw new \LogicException('This code should not be reached!');

    }

    private function canView(Product $product, Website $website) {
        return $website === $product->getWebsite();
    }
}
