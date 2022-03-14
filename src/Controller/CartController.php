<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CartRepository;
class CartController extends AbstractController
{
    private SessionInterface $session;
    private $productRepository;

    /**
     * @param $session
     */
    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->session->start();
        $this->session->set('flag', '1');
        $this->productRepository=$productRepository;
    }

    /**
     * @Route("/cart", name="cart")
     */
    public function cart(CartRepository $cartRepository): Response
    {
        $session = $this->session->getId();
        $items = $cartRepository->findBy(['sessionId' => $session]);

        return $this->render('cart/cart.html.twig', [
           'title' => 'Корзина',
            'items' => $items,
        ]);
    }

    /**
     * @Route ("/cart/add/{id<\d+>}", name="cartAdd")
     * @param int $id
     * @return Response
     */
    public function cartAdd(int $id, EntityManagerInterface $em): Response
    {
        $product=$this->productRepository->find($id);
        $sessionId = $this->session->getId();

        $cart = (new Cart())
            ->setProductItem($product)
            ->setCount(1)
            ->setSessionId($sessionId);
        $em->persist($cart);
        $em->flush();

        return $this->redirectToRoute('item', ['id' => $product->getId()]);
    }

}


