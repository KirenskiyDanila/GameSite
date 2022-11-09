<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Review;
use App\Entity\User;
use App\Form\ReviewFormType;
use App\Services\PageFormer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameAndReviewsController extends AbstractController
{
    #[Route('/game/{id}', name: 'app_game')]
    public function games(Game $game, ManagerRegistry $doctrine, Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

        $name = $session->get('name') ?? null;

        $review = new Review();
        $form = $this->createForm(ReviewFormType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $review = $form->getData();
            $review->setAuthor($doctrine->getRepository(User::class)->find(
                $this->container->get('security.token_storage')->getToken()->getUser()->getId()
            ));
            $review->setDate(new \DateTime('now'));
            $review->setGame($doctrine->getRepository(Game::class)->find($game->getId()));
            $review->setApproved(false);
            $em = $doctrine->getManager();
            $em->persist($review);
            $em->flush();
            return $this->redirect($game->getId());
        }

        $reviews = $doctrine->getRepository(Review::class)->getLastReviewsById($game->getId());

        $allReviews = $doctrine->getRepository(Review::class)->findBy(['game' => $game->getId()]);
        if ($this->container->get('security.token_storage')->getToken() != null) {
            $flag = true;
            foreach ($allReviews as $review) {
                if ($this->container->get('security.token_storage')->getToken()->getUser() == $review->getAuthor()) {
                    $flag = false;
                    break;
                }
            }
        }
        else {
            $flag = false;
        }

        $currentDate = (new \DateTime('now'));

        return $this->renderForm('game_and_reviews/game.html.twig', [
            'controller_name' => 'MainController',
            'game' => $game,
            'reviews' => $reviews,
            'user_name' => $name,
            'form' => $form,
            'currentDate' => $currentDate,
            'flag' => $flag
        ]);
    }

    #[Route('/game/{id}/reviews/{page}', name: 'app_game_reviews')]
    public function gameReview(Game $game, int $page, ManagerRegistry $doctrine, Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

        $name = $session->get('name') ?? null;

        $reviews = $doctrine->getRepository(Review::class)->getReviewsByGameId($game->getId(), $page);

        $count = $doctrine->getRepository(Review::class)->getCountById($game->getId());

        $pages = PageFormer::formPagination($page, $count);


        return $this->render('game_and_reviews/reviews.html.twig', [
            'controller_name' => 'MainController',
            'reviews' => $reviews,
            'user_name' => $name,
            'game' => $game,
            'actual_page' => $page,
            'pages' => $pages,
        ]);
    }

    #[Route('/games/{page}', name: 'app_games')]
    public function gameArchive(int $page, ManagerRegistry $doctrine, Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

        $name = $session->get('name') ?? null;

        $games = $doctrine->getRepository(Game::class)->getGames($page);

        $count = $doctrine->getRepository(Game::class)->getCount();

        $pages = PageFormer::formPagination($page, $count);

        return $this->render('game_and_reviews/games.html.twig', [
            'controller_name' => 'MainController',
            'user_name' => $name,
            'games' => $games,
            'actual_page' => $page,
            'pages' => $pages,
        ]);
    }

    #[Route('/games_search', name: 'app_search')]
    public function gameSearch(ManagerRegistry $doctrine, Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

        $content = $request->query->get('content');

        $name = $session->get('name') ?? null;

        $games = $doctrine->getRepository(Game::class)->getGamesBySearch($content);

        return $this->render('game_and_reviews/games.html.twig', [
            'controller_name' => 'MainController',
            'user_name' => $name,
            'games' => $games,
            'pages' => null
        ]);
    }
}
