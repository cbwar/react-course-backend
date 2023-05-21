<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Form\OrderType;
use App\Repository\MealRepository;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order')]
class OrderController extends AbstractController
{

    #[Route('', methods: ["POST"])]
    public function index(Request         $request,
                          MealRepository  $mealRepository,
                          ManagerRegistry $registry): JsonResponse
    {
        $order = new Order();

        foreach ($request->get('items') as $key => $value) {
            $meal = $mealRepository->find($key);
            if ($meal === null) {
                throw new RuntimeException('invalid meal in order');
            }
            $orderItem = (new OrderItem())
                ->setMeal($meal)
                ->setQuantity($value);
            $order->addItem($orderItem);
        }
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($order->getItems() as $item) {
                $registry->getManager()->persist($item);
            }
            $registry->getManager()->persist($order);
            $registry->getManager()->flush();

            return $this->json([
                'orderId' => $order->getId()
            ]);
        }
        throw new RuntimeException('invalid form data');
    }
}