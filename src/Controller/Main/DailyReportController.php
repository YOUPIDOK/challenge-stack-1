<?php

namespace App\Controller\Main;

use App\Entity\DailyReport;
use App\Form\DailyReport\SearchDailyReportType;
use App\Repository\DailyReportRepository;
use App\Security\Main\Voter\DailyReportVoter;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/rapport-journalier')]
#[IsGranted('ROLE_CLIENT')]
class DailyReportController extends AbstractController
{
    #[Route('/', name: 'daily_reports', methods: ['GET'])]
    public function index(Request $request, DailyReportRepository $dailyReportRepo): Response
    {
        $client = $this->getUser()->getClient();

        $form = $this->createForm(SearchDailyReportType::class);
        $form->handleRequest($request);

        $adapter = new QueryAdapter($dailyReportRepo->searchDailyReportQb($client, $form->get('start')->getData(), $form->get('end')->getData()));
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(10);
        $pager->setCurrentPage($request->query->get('page', 1));

        return $this->render('pages/dailyReport/index.html.twig', [
            'pager' => $pager,
            'form' => $form->createView()
        ]);
    }

    #[Route('rapport-du-jour', name: 'daily_report_show_current')]
    public function showOrCreateCurrent(DailyReportRepository $dailyReportRepository): Response
    {
        $client = $this->getUser()->getClient();
        $dailyReport = $client->getCurrentDailyReport();

        if ($dailyReport === null) {
            $dailyReport = new DailyReport();
            $dailyReport->setDate(new \DateTime());
            $dailyReport->setClient($this->getUser()->getClient());
            $dailyReportRepository->save($dailyReport, true);
        }

        return $this->redirectToRoute('daily_report_show', ['id' => $dailyReport->getId()]);
    }

    #[Route('/{id}', name: 'daily_report_show', methods: ['GET'])]
    public function show(DailyReport $dailyReport): Response
    {
        $this->denyAccessUnlessGranted(DailyReportVoter::ACCESS, $dailyReport);

        return $this->render('pages/dailyReport/show.html.twig', [
            'dailyReport' => $dailyReport,
        ]);
    }
}
