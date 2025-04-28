<?php

namespace App\Controller;

use App\Repository\CalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    // la route de l'affichage du calendrier
    #[Route('/main', name: 'main')]
    public function index(CalendarRepository  $calendar): Response
    {

        $events = $calendar->findAll();
        $rdvs = [];
        foreach ($events as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->getAllDay(),

            ];
        }
        $data = json_encode($rdvs);

        return $this->render('main/index.html.twig', compact('data'));
    }

    // route pour la page faq
    #[Route('/faq', name: 'faq')]
    public function profile_client(): Response
    {
        return $this->render('main/faq.html.twig');
    }
    //page de profil coach

    #[Route('/profile_coach', name: 'profile_coach')]
    public function profile_coach(): Response
    {
        return $this->render('main/profile_coach.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    //page account
    #[Route('/account', name: 'account')]
    public function account(): Response
    {
        return $this->render('main/account.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
