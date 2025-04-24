<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user')]
#[IsGranted('ROLE_USER')]
class UserController extends AbstractController
{
    #[Route('/orders', name: 'app_user_orders')]
    public function orders(Request $request, OrderRepository $orderRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
        $query = $orderRepository->createQueryBuilder('o')
            ->where('o.user = :user')
            ->setParameter('user', $user)
            ->orderBy('o.date', 'DESC')
            ->getQuery();

        $orders = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10 // items per page
        );

        return $this->render('user/orders.html.twig', [
            'orders' => $orders,
        ]);
    }
}