<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface; //ajout en bdd
use App\Entity\Alarme;

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
        if ($request->isMethod('POST')) {
            // Récupère l'utilisateur actuellement connecté
            $utilisateur = $this->getUser();
        
            // Récupère les données du formulaire
            $type = $request->request->get('type');
            $valeur = $request->request->get('valeur');
            
            $alarmeType = $request->request->get('alarme'); // Récupère la valeur du radio bouton
            // Détermine si l'alarme est inférieure ou supérieure
            $inf = $alarmeType === 'inf' ? true : false;
            $sup = $alarmeType === 'sup' ? true : false;
        
            // Crée une nouvelle instance d'Alarme
            $alarme = new Alarme();
            $alarme->setType($type);
            $alarme->setValeur($valeur);
            $alarme->setInf($inf);
            $alarme->setSup($sup);
        
            // Associe l'utilisateur à l'alarme
            $alarme->setUtilisateur($utilisateur);
        
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
