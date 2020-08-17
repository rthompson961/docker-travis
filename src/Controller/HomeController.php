<?php

namespace App\Controller;

use App\Entity\Fruit;
use App\Message\FruitMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $fruit = $this->getDoctrine()
                ->getRepository(Fruit::class)
                ->findAll();

        return $this->render('home/index.html.twig', [
            'fruit' => $fruit,
        ]);
    }

    /**
     * @Route("/check/{name}", name="expire")
     */
    public function expire(Fruit $fruit, MessageBusInterface $bus)
    {
        if (!$fruit) {
            throw $this->createNotFoundException('Fruit not found');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $fruit->setState('checking');
        $entityManager->flush();

        $bus->dispatch(new FruitMessage($fruit->getId()));

        return $this->redirectToRoute('home');
    }
}
