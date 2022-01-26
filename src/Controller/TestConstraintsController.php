<?php

namespace App\Controller;

use App\Entity\Rating;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestConstraintsController extends AbstractController
{
    #[Route('/test/constraints', name: 'test_constraints')]
    public function index(): Response
    {
        $rating = new Rating();


        return $this->render('test_constraints/index.html.twig', [
            'controller_name' => 'TestConstraintsController',
        ]);
    }
}
