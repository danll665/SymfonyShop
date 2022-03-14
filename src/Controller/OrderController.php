<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CartRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderController extends AbstractController
{
    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session){
        $this->session = $session;
        $this->session->start();
        $this->session->set('flag', '1');
    }
    /**
     * @Route("/order", name="Order")
     * @param Request $request
     * @return Response
     */
    public function Order(Request $request, EntityManagerInterface $em): Response
    {
        $order = new Order();

        $form = $this->createForm(OrderFormType::class, $order);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            if ($order instanceof Order){
                $sessionId = $this->session->getId();
                $order->setStatus(Order::STATUS_NEW_ORDER);
                $order->setSessionId($sessionId);
                $em->persist($order);
                $em->flush();
                $this->session->migrate();
            }

            return $this->redirectToRoute('index');
        }

        return $this->render('order/index.html.twig', [
            'title' => 'Оформление заказа',
            'form' => $form->createView(),
        ]);
    }
}
