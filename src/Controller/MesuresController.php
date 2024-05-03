<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MesuresController extends AbstractController
{
    #[Route('/mesures', name: 'app_mesures')]
    public function index(): Response
    {
        return $this->render('mesures/index.html.twig', [
            'controller_name' => 'MesuresController',
        ]);
    }
    #[Route('/mesures/all', name: 'app_mesures_all')]
    public function getJson(Request $request)
    {
        // V�rifier si la demande contient un fichier JSON
        if ($request->files->has('json_file')) {
            // R�cup�rer le fichier JSON
            $jsonFile = $request->files->get('json_file');

            // V�rifier si le fichier est valide
            if ($jsonFile->isValid() && $jsonFile->getClientOriginalExtension() === 'json') {
                // Lire le contenu du fichier JSON
                $jsonContent = file_get_contents($jsonFile->getPathname());

                // Convertir le JSON en tableau associatif
                $jsonData = json_decode($jsonContent, true);

                // Faire ce que vous avez besoin de faire avec les donn�es JSON
                // Par exemple, retourner les donn�es JSON en tant que r�ponse JSON
                return new JsonResponse($jsonData); 
            } else {
                // Le fichier n'est pas un fichier JSON valide
                return new JsonResponse(['error' => 'Invalid JSON file'], 400);
            }
        } else {
            // Aucun fichier JSON trouv� dans la demande
            return new JsonResponse(['error' => 'No JSON file found in the request'], 400);
        }
    }

    #[Route("/mesures/temperature", name: "meteo_temperature")]
    public function temperature(MesuresRepository $mesuresRepository): JsonResponse
    {
        // R�cup�rer la mesure la plus r�cente de la temp�rature
        $mesureTemperature = $mesuresRepository->findMostRecentTemperature();

        // V�rifier si une mesure de temp�rature a �t� trouv�e
        if ($mesureTemperature) {
            // Construire la r�ponse JSON
            $response = [
                'temperature' => $mesureTemperature->getTemperatureC(),
                'date_heure' => $mesureTemperature->getDateHeure()->format('Y-m-d H:i:s')
            ];
            // Renvoyer la r�ponse JSON
            return new JsonResponse($response);
        } else {
            // Si aucune mesure de temp�rature n'est trouv�e, renvoyer une r�ponse vide
            return new JsonResponse([], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    #[Route("/mesures/historique/{date}", name: "meteo_historique")]
    public function historique(string $date, MesuresRepository $mesuresRepository): JsonResponse
    {
        // Convertir la cha�ne de date en objet DateTime
        $dateObj = new \DateTime($date);

        // R�cup�rer la mesure de temp�rature pour la date sp�cifi�e
        $mesureTemperature = $mesuresRepository->findByDate($dateObj);

        // V�rifier si une mesure de temp�rature a �t� trouv�e
        if ($mesureTemperature) {
            // Construire la r�ponse JSON
            $response = [
                'temperature' => $mesureTemperature->getTemperatureC(),
                'date_heure' => $mesureTemperature->getDateHeure()->format('Y-m-d H:i:s')
            ];
            // Renvoyer la r�ponse JSON
            return new JsonResponse($response);
        } else {
            // Si aucune mesure de temp�rature n'est trouv�e, renvoyer une r�ponse vide
            return new JsonResponse([], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    #[Route("/mesures/humidite", name: "mesures_humidite")]
    public function humidite(MesuresRepository $mesuresRepository): JsonResponse
    {
        // R�cup�rer la mesure d'humidit� la plus r�cente
        $mesureHumidite = $mesuresRepository->findMostRecentHumidite();

        // V�rifier si une mesure d'humidit� a �t� trouv�e
        if ($mesureHumidite) {
            // Construire la r�ponse JSON
            $response = [
                'humidite' => $mesureHumidite->getHumidite(),
                'date_heure' => $mesureHumidite->getDateHeure()->format('Y-m-d H:i:s')
            ];
            // Renvoyer la r�ponse JSON
            return new JsonResponse($response);
        } else {
            // Si aucune mesure d'humidit� n'est trouv�e, renvoyer une r�ponse vide
            return new JsonResponse([], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    #[Route("/mesures/luminosite", name: "mesures_luminosite")]
    public function luminosite(MesuresRepository $mesuresRepository): JsonResponse
    {
        // R�cup�rer la mesure de luminosit� la plus r�cente
        $mesureLuminosite = $mesuresRepository->findMostRecentLuminosite();

        // V�rifier si une mesure de luminosit� a �t� trouv�e
        if ($mesureLuminosite) {
            // Construire la r�ponse JSON
            $response = [
                'luminosite' => $mesureLuminosite->getLuminosite(),
                'date_heure' => $mesureLuminosite->getDateHeure()->format('Y-m-d H:i:s')
            ];
            // Renvoyer la r�ponse JSON
            return new JsonResponse($response);
        } else {
            // Si aucune mesure de luminosit� n'est trouv�e, renvoyer une r�ponse vide
            return new JsonResponse([], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    #[Route("/mesures/pression", name: "mesures_pression")]
    public function pression(MesuresRepository $mesuresRepository): JsonResponse
    {
        // R�cup�rer la mesure de pression la plus r�cente
        $mesurePression = $mesuresRepository->findMostRecentPression();

        // V�rifier si une mesure de pression a �t� trouv�e
        if ($mesurePression) {
            // Construire la r�ponse JSON
            $response = [
                'pression' => $mesurePression->getPression(),
                'date_heure' => $mesurePression->getDateHeure()->format('Y-m-d H:i:s')
            ];
            // Renvoyer la r�ponse JSON
            return new JsonResponse($response);
        } else {
            // Si aucune mesure de pression n'est trouv�e, renvoyer une r�ponse vide
            return new JsonResponse([], JsonResponse::HTTP_NOT_FOUND);
        }
    }


}
