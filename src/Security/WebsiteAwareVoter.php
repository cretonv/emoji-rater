<?php

namespace App\Security;

use App\Context\WebsiteContext;
use App\Entity\Product;
use App\Entity\Website;
use App\Entity\WebsiteAwareInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class WebsiteAwareVoter extends Voter {

    const READ_WA_ITEM = 'READ_WA_ITEM';

    public function __construct(
        private WebsiteContext $websiteContext
    ) {
    }

    protected function supports(string $attribute, $subject) {
        if (!in_array($attribute, [self::READ_WA_ITEM])) {
            return false;
        }
        if (!($subject instanceof WebsiteAwareInterface)) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool {
        $website = $this->websiteContext->getWebsite();
        /** @var WebsiteAwareInterface $subject */
        switch ($attribute) {
            case self::READ_WA_ITEM:
                return $this->canView($subject, $website);
        }

        throw new \LogicException('This code should not be reached!');

    }
    private function canView(WebsiteAwareInterface $subject, Website $website): bool {
        return $website === $subject->getWebsite();
    }
}
