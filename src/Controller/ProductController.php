<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Product;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    private $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository=$productRepository;
    }
    /**
     * @Route("/product/list", name="list")
     * @return Response
     */
    public function productList(): Response
    {
        $product = $this->productRepository->findAll();
        return $this->render('product/list.html.twig',[
            'products' => $product,
        ]);
    }

    /**
     * @Route("/product/item/{id}", name="item")
     * @param int $id
     * @return Response
     */
    public function productItem(int $id): Response
    {

        $product = $this->productRepository->find($id);
        return $this->render('product/item.html.twig',[
            'title' => 'PRODUCT ITEM ' . $id,
            'product' => $product,
            'id' => $id,
        ]);
    }
}


