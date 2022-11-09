<?php

namespace App\Controller\Admin;

use App\Entity\Game;
use App\Entity\Platform;
use App\Entity\Price;
use App\Entity\Publisher;
use App\Entity\Review;
use App\Entity\Studio;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(GameCrudController::class)->generateUrl();

        return $this->redirect($url);

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('GameSite');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoRoute('На главную страницу', 'fas fa-home', 'app_main');
        if (in_array("ROLE_SUPER", $this->container->get('security.token_storage')->getToken()->getUser()->getRoles())) {
            yield MenuItem::linkToCrud('Пользователи', 'fas fa-bars', User::class);
        }
        yield MenuItem::linkToCrud('Издатели', 'fas fa-bars', Publisher::class);
        yield MenuItem::linkToCrud('Цены', 'fas fa-bars', Price::class);
        yield MenuItem::linkToCrud('Обзоры', 'fas fa-bars', Review::class);
        yield MenuItem::linkToCrud('Студии', 'fas fa-bars', Studio::class);
        yield MenuItem::linkToCrud('Платформы', 'fas fa-bars', Platform::class);
        yield MenuItem::linkToCrud('Игры', 'fas fa-bars', Game::class);

    }
}
