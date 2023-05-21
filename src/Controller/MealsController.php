<?php

namespace App\Controller;

use App\Repository\MealRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class MealsController extends AbstractController
{
    #[Route('/meals', name: 'app_meals')]
    public function index(MealRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll());
    }
}
