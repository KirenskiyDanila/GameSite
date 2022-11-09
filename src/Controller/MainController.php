<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Platform;
use App\Entity\Publisher;
use App\Entity\Review;
use App\Entity\Studio;
use App\Entity\User;
use App\Form\ReviewFormType;
use App\Services\PageFormer;
use Doctrine\Persistence\ManagerRegistry;
use PDO;
use PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $session = new Session();

        $name = $session->get('name') ?? null;

        $games = $doctrine->getRepository(Game::class)->getReleasedGames();

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'games' => $games,
            'user_name' => $name
        ]);
    }

    #[Route('/publishers/{page}', name: 'app_publishers')]
    public function publishers(int $page, ManagerRegistry $doctrine, Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

        $name = $session->get('name') ?? null;

        $publishers = $doctrine->getRepository(Publisher::class)->findAll();

        $count = $doctrine->getRepository(Publisher::class)->getCount();

        $pages = PageFormer::formPagination($page, $count);

        return $this->render('main/publishers.html.twig', [
            'controller_name' => 'MainController',
            'user_name' => $name,
            'publishers' => $publishers,
            'pages' => $pages,
            'actual_page' => $page
        ]);
    }

    #[Route('/platforms/{page}', name: 'app_platforms')]
    public function platforms(int $page, ManagerRegistry $doctrine, Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

        $name = $session->get('name') ?? null;

        $platforms = $doctrine->getRepository(Platform::class)->findAll();

        $count = $doctrine->getRepository(Platform::class)->getCount();

        $pages = PageFormer::formPagination($page, $count);

        return $this->render('main/platforms.html.twig', [
            'controller_name' => 'MainController',
            'user_name' => $name,
            'platforms' => $platforms,
            'pages' => $pages,
            'actual_page' => $page
        ]);
    }

    #[Route('/lists', name: 'app_lists')]
    public function lists(Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

        $name = $session->get('name') ?? null;

        return $this->render('main/lists.html.twig', [
            'controller_name' => 'MainController',
            'user_name' => $name,
        ]);
    }

    #[Route('/procedure', name: 'procedure')]
    public function procedure(): Response
    {

        $ini_array = parse_ini_file('parameters.ini');
        try {
            $pdo = new PDO('pgsql:host=' . $ini_array['host'] . ';port=' . $ini_array['port'] .
                ';dbname=' . $ini_array['name'] . ';user=' . $ini_array['login'] . ';password=' . $ini_array['password']);
        } catch (PDOException $exception) {
            echo "Ошибка подключения к БД: " . $exception->getMessage();
            die();
        }
        $pdo->beginTransaction();
        $sql = "CALL delete_bad_users();";
        $result = $pdo->prepare($sql);
        $result->execute();
        $pdo->commit();

        return $this->redirectToRoute('app_main');
    }

}
