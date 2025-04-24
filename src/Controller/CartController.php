<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart_index', methods: ['GET'])]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        $cart = $session->get('cart', []);
        $products = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $productRepository->find($productId);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->getPrice() * $quantity
                ];
                $total += $product->getPrice() * $quantity;
            }
        }

        return $this->render('cart/index.html.twig', [
            'products' => $products,
            'total' => $total
        ]);
    }

    #[Route('/add/{id}', name: 'app_cart_add', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function addToCart(Product $product, Request $request, SessionInterface $session): Response
    {
        try {
            // Get the cart from the session
            $cart = $session->get('cart', []);

            // Get quantity from request
            $quantity = (int) $request->request->get('quantity', 1);

            // Validate quantity
            if ($quantity < 1) {
                $quantity = 1;
            }

            // Check stock if user is admin
            if ($this->isGranted('ROLE_ADMIN') && $quantity > $product->getStock()) {
                $quantity = $product->getStock();
            }

            // Add the product to the cart
            if (!isset($cart[$product->getId()])) {
                $cart[$product->getId()] = $quantity;
            } else {
                $cart[$product->getId()] += $quantity;
            }

            // Save the cart back to the session
            $session->set('cart', $cart);

            // Check if it's an AJAX request
            if ($request->headers->get('X-Requested-With') === 'XMLHttpRequest') {
                return new JsonResponse([
                    'success' => true,
                    'message' => sprintf('"%s" a été ajouté à votre panier', $product->getName())
                ]);
            }

            // Regular form submission
            $this->addFlash('success', 'Produit ajouté au panier avec succès!');
            return $this->redirectToRoute('app_cart_index');

        } catch (\Exception $e) {
            if ($request->headers->get('X-Requested-With') === 'XMLHttpRequest') {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de l\'ajout au panier'
                ]);
            }

            $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout au panier');
            return $this->redirectToRoute('app_cart_index');
        }
    }

    #[Route('/validate', name: 'app_cart_validate', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function validateCart(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $cart = $session->get('cart', []);

        if (empty($cart)) {
            $this->addFlash('warning', 'Votre panier est vide!');
            return $this->redirectToRoute('app_cart_index');
        }

        // Create new order
        $order = new Order();
        $order->setUser($this->getUser());
        $order->setDate(new \DateTime());
        $order->setStatus('Commande passée');

        // Add products from cart to order
        foreach ($cart as $productId => $quantity) {
            $product = $entityManager->getRepository(Product::class)->find($productId);
            if ($product) {
                for ($i = 0; $i < $quantity; $i++) {
                    $order->addProduct($product);
                }
            }
        }

        // Save the order
        $entityManager->persist($order);
        $entityManager->flush();

        // Clear the cart
        $session->remove('cart');

        $this->addFlash('success', 'Votre commande a été créée avec succès!');

        return $this->redirectToRoute('app_order_show', ['id' => $order->getId()]);
    }

    #[Route('/clear', name: 'app_cart_clear', methods: ['GET'])]
    public function clearCart(SessionInterface $session): Response
    {
        $session->remove('cart');
        $this->addFlash('success', 'Votre panier a été vidé avec succès!');
        return $this->redirectToRoute('app_cart_index');
    }
}