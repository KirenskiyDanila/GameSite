<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Studio;
use App\Services\PageFormer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudiosController extends AbstractController
{
    #[Route('/subscriptions', name: 'app_subscriptions')]
    public function subscriptions(ManagerRegistry $doctrine, Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

        $name = $session->get('name') ?? null;

        $studios = $doctrine->getRepository(Studio::class)->
        getStudiosBySubscriber($this->container->get('security.token_storage')->getToken()->getUser()->getId());


        return $this->render('studios/user_studios.html.twig', [
            'controller_name' => 'MainController',
            'user_name' => $name,
            'studios' => $studios
        ]);
    }

    #[Route('/subscribe/{id}', name: 'app_subscribe')]
    public function subscribe(Studio $studio, ManagerRegistry $doctrine, Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

        $studio->addSubscriber($this->container->get('security.token_storage')->getToken()->getUser());

        $em = $doctrine->getManager();

        $em->persist($studio);
        $em->flush();

        return $this->redirectToRoute('app_studios', ['page' => 1]);
    }

    #[Route('/unsubscribe/{id}', name: 'app_unsubscribe')]
    public function unsubscribe(Studio $studio, ManagerRegistry $doctrine, Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

        $studio->removeSubscriber($this->container->get('security.token_storage')->getToken()->getUser());

        $em = $doctrine->getManager();

        $em->persist($studio);
        $em->flush();

        return $this->redirectToRoute('app_subscriptions');
    }

    #[Route('/subscriptions/games/', name: 'app_subscriptions_games')]
    public function subscriptionGames(ManagerRegistry $doctrine, Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

        $name = $session->get('name') ?? null;

        $games = $doctrine->getRepository(Game::class)->getGamesBySubscribtion(
            $this->container->get('security.token_storage')->getToken()->getUser()->getId()
        );


        return $this->render('game_and_reviews/games.html.twig', [
            'controller_name' => 'MainController',
            'user_name' => $name,
            'games' => $games,
            'actual_page' => 1,
            'pages' => 1,
        ]);
    }

    #[Route('/studios/{page}', name: 'app_studios')]
    public function studios(int $page, ManagerRegistry $doctrine, Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

        $name = $session->get('name') ?? null;

        $studios = $doctrine->getRepository(Studio::class)->findAll();

        $count = $doctrine->getRepository(Studio::class)->getCount();

        $pages = PageFormer::formPagination($page, $count);

        return $this->render('studios/studios.html.twig', [
            'controller_name' => 'MainController',
            'user_name' => $name,
            'studios' => $studios,
            'pages' => $pages,
            'actual_page' => $page

        ]);
    }
}
