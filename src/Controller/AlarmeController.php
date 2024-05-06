<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface; //ajout en bdd
use App\Entity\Alarme;
use Symfony\Component\Security\Core\Security;

class AlarmeController extends AbstractController
{

    #[Route('/alarme', name: 'app_alarme')]
    public function index(): Response
    {
        return $this->render('alarme/index.html.twig', [
            'controller_name' => 'AlarmeController',
        ]);
    }

    // Route pour ajouter une alarme
    #[Route('/alarmes/ajouter', name: 'ajouter_alarme')]
    public function ajouterAlarme(Request $request, EntityManagerInterface $entityManager): Response
    {

        // Vérifie si la requête est de type POST
        if($request->isMethod('POST'))
        {    

            // Récupère les données du formulaire
            $type = $request->request->get('type');
            $valeur = $request->request->get('valeur');
            $inf = $request->request->get('inf');
            $sup = $request->request->get('sup');

            // Crée une nouvelle instance d'Alarme
            $alarme = new Alarme();
            $alarme->setType($type);
            $alarme->setValeur($valeur);
            $alarme->setInf($inf);
            $alarme->setSup($sup);

            // Persiste l'alarme dans la base de données
            $entityManager->persist($alarme);
            $entityManager->flush();

            // Redirige vers une page de confirmation ou une autre route si nécessaire
            return $this->render('alarme/ajouterAlarme.html.twig', [
                'messageConfirmation' => 'Alarme ajoutée avec succès.',
            ]);    
        }

        return $this->render('alarme/ajouterAlarme.html.twig', [
            'messageConfirmation' => '',
        ]);    
    }
}
