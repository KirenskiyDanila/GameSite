<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\User;
use App\Services\PageFormer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/{id}/{page}', name: 'app_user_reviews')]
    public function userReviews(User $user, int $page, ManagerRegistry $doctrine, Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

        $name = $session->get('name') ?? null;

        $reviews = $doctrine->getRepository(Review::class)->getReviewsByUserId($user->getId(), $page);

        $count = count($reviews);

        $pages = PageFormer::formPagination($page, $count);

        return $this->render('user/user_reviews.html.twig', [
            'controller_name' => 'MainController',
            'user_name' => $name,
            'reviews' => $reviews,
            'pages' => $pages,
            'actual_page' => $page

        ]);
    }
}
