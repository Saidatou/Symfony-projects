<?php

namespace App\Controller;

use DateTime;
use App\Entity\Calendar;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    #[Route('/api', name: 'api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }


    #[Route('/api/{id}/edit', name: 'api_event_edit', methods: ['PUT'])]
    //La method : ‘PUT’  doit permettre de mettre à jour un enregistrement ou le créer s’il n’existe pas.  L’inconvenient c’est qu’on doit récupérer toutes les données en globalité. 
    public function majEvent(?calendar $calendar, Request $request): Response
    {


        // On récupère les données envoyées par fullCalender
        $donnees = json_decode($request->getContent());
        //verifier si l'on a toutes les données, on ne vérifiera pas la date de fin pour RDV qui durent toute la journée, ni la case à cocher all_day
        if ( //est ce que j'ai la donnée titre, est ce que cette donné existe? c'est objet et s'il n'est pas vide
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->description) && !empty($donnees->description) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor) &&
            isset($donnees->borderColor) && !empty($donnees->borderColor) &&
            isset($donnees->textColor) && !empty($donnees->textColor)

        ) {
            //si j'ai ces objets, les données sont donc complets 
            // On inititilise un code
            $code = 200; // le code 200 signifie c'est bon j'ai mis à jour et code 201 signifie que j'ai créee
            // On vérifie si l'id existe
            if (!$calendar) {
                //On instancie un rendez-vous
                $calendar = new Calendar;
                // On change le code
                $code = 201;
            }
            // On hydrate l'objet avec les données
            $calendar->setTitle($donnees->title);
            $calendar->setStart(new DateTime($donnees->start)); // pour setEnd on doit d'abord vérifiéé si on ture dans all day 
            if ($donnees->allDay) {
                $calendar->setEnd(new DateTime($donnees->start));
            } else {
                $calendar->setEnd(new DateTime($donnees->end));
            }
            $calendar->setAllDay($donnees->allDay);
            $calendar->setDescription($donnees->description);
            $calendar->setBackgroundColor($donnees->backgroundColor);
            $calendar->setBorderColor($donnees->borderColor);
            $calendar->setTextColor($donnees->textColor);

            $em = $this->getDoctrine()->getManager();
            $em->persist($calendar);
            $em->flush();

            // On retourne un code
            return new response('OK', $code);
        } else {
            //sinon elles sont incomplets, alors j'envoie une reponse de http fondation
            return new Response('Données incomplètes', 404);
        }


        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}
