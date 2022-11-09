<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Platform;
use App\Entity\Review;
use App\Entity\User;
use App\Services\PageFormer;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Fpdf\Fpdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    #[Route('/report', name: 'report')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

        $name = $session->get('name') ?? null;

        return $this->render('report/report.html.twig', [
            'controller_name' => 'MainController',
            'user_name' => $name,
        ]);
    }

    #[Route('/month_report', name: 'month_report')]
    public function monthReport(ManagerRegistry $doctrine, Request $request): Response
    {
        $dompdf = new Dompdf();

        $current_date = new \DateTime('now');

        $countReleasedGames = $doctrine->getRepository(Game::class)->getReleasedCountByMonth();
        $countAnnouncedGames = $doctrine->getRepository(Game::class)->getAnnouncedCountByMonth();

        $countApprovedReviews = $doctrine->getRepository(Review::class)->getApprovedCountByMonth();
        $countDisapprovedReviews = $doctrine->getRepository(Review::class)->getDisapprovedCountByMonth();
        $countUncheckedReviews = $doctrine->getRepository(Review::class)->getUncheckedCountByMonth();
        $countReviews = $doctrine->getRepository(Review::class)->getCountByMonth();

        $countUsers = $doctrine->getRepository(User::class)->getCountByMonth();




        $dompdf->loadHtml('
            <html><head><style>body { font-family: DejaVu Sans }</style>
            <h1>Месячный отчет от ' . date_format($current_date, 'Y-m-d') . '</h1>
            <br>
            <br>
            <p>Вышедших игр за месяц -  ' . $countReleasedGames . '</p>
            <br>
            <p>Анонсированных игр за месяц - ' . $countAnnouncedGames . '</p>
            <br>
            <br>
            <p>Всего обзоров за месяц - ' . $countReviews . '</p>
            <br>
            <p>Одобренных обзоров за месяц - ' . $countApprovedReviews . '</p>
            <br>
            <p>Неодобренных обзоров за месяц - ' . $countDisapprovedReviews . '</p>
            <br>
            <p>Не просмотренных обзоров за месяц - ' . $countUncheckedReviews . '</p>
            <br>
            <br>
            <p>Новых пользователей за месяц - ' . $countUsers . '</p>');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('Month_report_' . date_format($current_date, 'Y-m-d') .'.pdf');

        return $this->redirectToRoute('report');
    }

    #[Route('/year_report', name: 'year_report')]
    public function yearReport(ManagerRegistry $doctrine, Request $request): Response
    {
        $dompdf = new Dompdf();

        $current_date = new \DateTime('now');

        $countReleasedGames = $doctrine->getRepository(Game::class)->getReleasedCountByYear();
        $countAnnouncedGames = $doctrine->getRepository(Game::class)->getAnnouncedCountByYear();

        $countApprovedReviews = $doctrine->getRepository(Review::class)->getApprovedCountByYear();
        $countDisapprovedReviews = $doctrine->getRepository(Review::class)->getDisapprovedCountByYear();
        $countUncheckedReviews = $doctrine->getRepository(Review::class)->getUncheckedCountByYear();
        $countReviews = $doctrine->getRepository(Review::class)->getCountByYear();

        $countUsers = $doctrine->getRepository(User::class)->getCountByYear();




        $dompdf->loadHtml('
            <html><head><style>body { font-family: DejaVu Sans }</style>
            <h1>Годовой отчет от ' . date_format($current_date, 'Y-m-d') . '</h1>
            <br>
            <br>
            <p>Вышедших игр за год -  ' . $countReleasedGames . '</p>
            <br>
            <p>Анонсированных игр за год - ' . $countAnnouncedGames . '</p>
            <br>
            <br>
            <p>Всего обзоров за год - ' . $countReviews . '</p>
            <br>
            <p>Одобренных обзоров за год - ' . $countApprovedReviews . '</p>
            <br>
            <p>Неодобренных обзоров за год - ' . $countDisapprovedReviews . '</p>
            <br>
            <p>Не просмотренных обзоров за год - ' . $countUncheckedReviews . '</p>
            <br>
            <br>
            <p>Новых пользователей за год - ' . $countUsers . '</p>');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('Year_report_' . date_format($current_date, 'Y-m-d') .'.pdf');

        return $this->redirectToRoute('report');
    }

    #[Route('/AllTime_report', name: 'AllTime_report')]
    public function AllTimeReport(ManagerRegistry $doctrine, Request $request): Response
    {
        $dompdf = new Dompdf();

        $current_date = new \DateTime('now');

        $countGames = $doctrine->getRepository(Game::class)->getCount();

        $countApprovedReviews = $doctrine->getRepository(Review::class)->getApprovedCount();
        $countDisapprovedReviews = $doctrine->getRepository(Review::class)->getDisapprovedCount();
        $countUncheckedReviews = $doctrine->getRepository(Review::class)->getUncheckedCount();
        $countReviews = $doctrine->getRepository(Review::class)->getCount();

        $countUsers = $doctrine->getRepository(User::class)->getCount();




        $dompdf->loadHtml('
            <html><head><style>body { font-family: DejaVu Sans }</style>
            <h1> Общий отчет от ' . date_format($current_date, 'Y-m-d') . '</h1>
            <br>
            <br>
            <p>Всего игр -  ' . $countGames . '</p>
            <br>
            <br>
            <p>Всего обзоров- ' . $countReviews . '</p>
            <br>
            <p>Одобренных обзоров - ' . $countApprovedReviews . '</p>
            <br>
            <p>Неодобренных обзоров - ' . $countDisapprovedReviews . '</p>
            <br>
            <p>Не просмотренных обзоров - ' . $countUncheckedReviews . '</p>
            <br>
            <br>
            <p>Пользователей - ' . $countUsers . '</p>');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('AllTime_report_' . date_format($current_date, 'Y-m-d') .'.pdf');

        return $this->redirectToRoute('report');
    }
}
