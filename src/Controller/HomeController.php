<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository, PaginatorInterface $paginator): Response
    {
        $query = $productRepository->createQueryBuilder('p')
            ->orderBy('p.name', 'ASC')
            ->getQuery();

        $products = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            12 // items per page
        );

        return $this->render('home/index.html.twig', [
            'products' => $products,
            'categories' => $categoryRepository->findAll(),
        ]);
    }
}