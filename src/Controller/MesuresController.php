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
        // Vérifier si la demande contient un fichier JSON
        if ($request->files->has('json_file')) {
            // Récupérer le fichier JSON
            $jsonFile = $request->files->get('json_file');

            // Vérifier si le fichier est valide
            if ($jsonFile->isValid() && $jsonFile->getClientOriginalExtension() === 'json') {
                // Lire le contenu du fichier JSON
                $jsonContent = file_get_contents($jsonFile->getPathname());

                // Convertir le JSON en tableau associatif
                $jsonData = json_decode($jsonContent, true);

                // Faire ce que vous avez besoin de faire avec les données JSON
                // Par exemple, retourner les données JSON en tant que réponse JSON
                return new JsonResponse($jsonData); 
            } else {
                // Le fichier n'est pas un fichier JSON valide
                return new JsonResponse(['error' => 'Invalid JSON file'], 400);
            }
        } else {
            // Aucun fichier JSON trouvé dans la demande
            return new JsonResponse(['error' => 'No JSON file found in the request'], 400);
        }
    }

    #[Route("/mesures/temperature", name: "meteo_temperature")]
    public function temperature(MesuresRepository $mesuresRepository): JsonResponse
    {
        // Récupérer la mesure la plus récente de la température
        $mesureTemperature = $mesuresRepository->findMostRecentTemperature();

        // Vérifier si une mesure de température a été trouvée
        if ($mesureTemperature) {
            // Construire la réponse JSON
            $response = [
                'temperature' => $mesureTemperature->getTemperatureC(),
                'date_heure' => $mesureTemperature->getDateHeure()->format('Y-m-d H:i:s')
            ];
            // Renvoyer la réponse JSON
            return new JsonResponse($response);
        } else {
            // Si aucune mesure de température n'est trouvée, renvoyer une réponse vide
            return new JsonResponse([], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    #[Route("/mesures/historique/{date}", name: "meteo_historique")]
    public function historique(string $date, MesuresRepository $mesuresRepository): JsonResponse
    {
        // Convertir la chaîne de date en objet DateTime
        $dateObj = new \DateTime($date);

        // Récupérer la mesure de température pour la date spécifiée
        $mesureTemperature = $mesuresRepository->findByDate($dateObj);

        // Vérifier si une mesure de température a été trouvée
        if ($mesureTemperature) {
            // Construire la réponse JSON
            $response = [
                'temperature' => $mesureTemperature->getTemperatureC(),
                'date_heure' => $mesureTemperature->getDateHeure()->format('Y-m-d H:i:s')
            ];
            // Renvoyer la réponse JSON
            return new JsonResponse($response);
        } else {
            // Si aucune mesure de température n'est trouvée, renvoyer une réponse vide
            return new JsonResponse([], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    #[Route("/mesures/humidite", name: "mesures_humidite")]
    public function humidite(MesuresRepository $mesuresRepository): JsonResponse
    {
        // Récupérer la mesure d'humidité la plus récente
        $mesureHumidite = $mesuresRepository->findMostRecentHumidite();

        // Vérifier si une mesure d'humidité a été trouvée
        if ($mesureHumidite) {
            // Construire la réponse JSON
            $response = [
                'humidite' => $mesureHumidite->getHumidite(),
                'date_heure' => $mesureHumidite->getDateHeure()->format('Y-m-d H:i:s')
            ];
            // Renvoyer la réponse JSON
            return new JsonResponse($response);
        } else {
            // Si aucune mesure d'humidité n'est trouvée, renvoyer une réponse vide
            return new JsonResponse([], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    #[Route("/mesures/luminosite", name: "mesures_luminosite")]
    public function luminosite(MesuresRepository $mesuresRepository): JsonResponse
    {
        // Récupérer la mesure de luminosité la plus récente
        $mesureLuminosite = $mesuresRepository->findMostRecentLuminosite();

        // Vérifier si une mesure de luminosité a été trouvée
        if ($mesureLuminosite) {
            // Construire la réponse JSON
            $response = [
                'luminosite' => $mesureLuminosite->getLuminosite(),
                'date_heure' => $mesureLuminosite->getDateHeure()->format('Y-m-d H:i:s')
            ];
            // Renvoyer la réponse JSON
            return new JsonResponse($response);
        } else {
            // Si aucune mesure de luminosité n'est trouvée, renvoyer une réponse vide
            return new JsonResponse([], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    #[Route("/mesures/pression", name: "mesures_pression")]
    public function pression(MesuresRepository $mesuresRepository): JsonResponse
    {
        // Récupérer la mesure de pression la plus récente
        $mesurePression = $mesuresRepository->findMostRecentPression();

        // Vérifier si une mesure de pression a été trouvée
        if ($mesurePression) {
            // Construire la réponse JSON
            $response = [
                'pression' => $mesurePression->getPression(),
                'date_heure' => $mesurePression->getDateHeure()->format('Y-m-d H:i:s')
            ];
            // Renvoyer la réponse JSON
            return new JsonResponse($response);
        } else {
            // Si aucune mesure de pression n'est trouvée, renvoyer une réponse vide
            return new JsonResponse([], JsonResponse::HTTP_NOT_FOUND);
        }
    }


}
