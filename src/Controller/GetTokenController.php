<?php

namespace App\Controller;

use App\Entity\Website;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
class GetTokenController extends AbstractController
{

    public function __construct(
        private RequestStack      $requestStack,
        private EntityManagerInterface $em,
    ) {
    }

    private function generateToken() {
        return str_replace('-', '', Uuid::v4());
    }

    /**
     * Get a token
     * @param $data
     * @return string|null
     */
    public function __invoke($data) {
        $url = $this->requestStack->getCurrentRequest()->query->get('website_url');
        if (empty($url)) {
            throw new BadRequestHttpException('Parameter website_url must be provided when registering a website.');
        }
        $website = new Website();
        $website->setDomain($url);
        $website->setToken($this->generateToken());

        $this->em->persist($website);
        $this->em->flush();

        return $website->getToken();
    }
}
