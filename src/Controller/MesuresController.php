<?php

namespace App\Controller;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Mesures;
use Doctrine\ORM\EntityManagerInterface; //ajout en bdd
use App\Repository\MesuresRepository;
use App\Entity\User;
use App\Entity\Alarme;

class MesuresController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/mesures', name: 'app_mesures')]
    public function index(MesuresRepository $mesuresRepository): Response
    {
        $mesures = $mesuresRepository->findAll();

        return $this->render('mesures/index.html.twig', [
            'mesures' => $mesures,
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

    // #[Route('/mesures/ajout', name: 'mesures_post', methods: ['POST'])]

    #[Route('/mesures/ajout', name: 'mesures_post')]
    public function ajouterMesure(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer les données JSON de la requête
        // $donnees = json_decode($request->getContent(), true);
        $donnees = json_decode('{"temperature_c":24.95,"humidite":46.94,"pression":98.44,"luminosite":255.00}', true);

        // Vérifier si les données sont présentes et complètes
        if (!isset($donnees['temperature_c'], $donnees['humidite'], $donnees['pression'], $donnees['luminosite'])) {
            return new JsonResponse(['message' => 'Données incomplètes'], 400);
        }

        // Récupérer les données individuelles
        $temperature = $donnees['temperature_c'];
        $humidite = $donnees['humidite'];
        $pression = $donnees['pression'];
        $luminosite = $donnees['luminosite'];
        $temperatureF = $temperature * (9 / 5) + 32;

        
        $mesure = new Mesures();
        $dateObj = new \DateTime();

        $mesure->setTemperatureC($temperature);
        $mesure->setTemperatureF($temperatureF);
        $mesure->setHumidite($humidite);
        $mesure->setPression($pression);
        $mesure->setLuminosite($luminosite);
        $mesure->setDateHeure($dateObj);

        $entityManager->persist($mesure);
        $entityManager->flush();
        

        // Récupérer le repository de l'entité User
        $utilisateur = $this->getUser();

        $userRepository = $entityManager->getRepository(User::class);
        $alarmeRepo = $entityManager->getRepository(Alarme::class);

        $userId = $utilisateur->getUserIdentifier();
        $user = $userRepository->findOneBy(['username' => $userId]);
    
        $alarmes = $alarmeRepo->findAll();

        foreach ($alarmes as $alarme) {
            if($alarme->getUtilisateur() == $user){
                if($alarme->getType() == 'temperature'){
                    if($alarme->isInf() == true){
                        if($alarme->getValeur() > $donnees['temperature_c']){
                            $response = $this->httpClient->request('GET', 'http://192.168.37.68/eteindreAlarme', []);
    
                            if ($response->getStatusCode() === 200) {      
                                // Retourner une réponse JSON indiquant que l'alarme a été éteinte avec succès
                                return new JsonResponse(['message' => 'L\'alarme a été éteinte avec succès'], 200);
                            } else {
                                // Retourner une réponse JSON en cas d'erreur
                                return new JsonResponse(['message' => 'Erreur lors de l\'envoi de la commande à l\'ESP32'], 500);
                            }                        }
                    }
                    if($alarme->isSup() == true){
                        if($alarme->getValeur() < $donnees['temperature_c']){
                            $response = $this->httpClient->request('GET', 'http://192.168.37.68/eteindreAlarme', []);
    
                            if ($response->getStatusCode() === 200) {      
                                // Retourner une réponse JSON indiquant que l'alarme a été éteinte avec succès
                                return new JsonResponse(['message' => 'L\'alarme a été éteinte avec succès'], 200);
                            } else {
                                // Retourner une réponse JSON en cas d'erreur
                                return new JsonResponse(['message' => 'Erreur lors de l\'envoi de la commande à l\'ESP32'], 500);
                            }                        }
                    }
                }
                if($alarme->getType() == 'humidite'){
                    if($alarme->isInf() == true){
                        if($alarme->getValeur() > $donnees['humidite']){
                            $response = $this->httpClient->request('GET', 'http://192.168.37.68/eteindreAlarme', []);
    
                            if ($response->getStatusCode() === 200) {      
                                // Retourner une réponse JSON indiquant que l'alarme a été éteinte avec succès
                                return new JsonResponse(['message' => 'L\'alarme a été éteinte avec succès'], 200);
                            } else {
                                // Retourner une réponse JSON en cas d'erreur
                                return new JsonResponse(['message' => 'Erreur lors de l\'envoi de la commande à l\'ESP32'], 500);
                            }                        }
                    }
                    if($alarme->isSup() == true){
                        if($alarme->getValeur() < $donnees['humidite']){
                            $response = $this->httpClient->request('GET', 'http://192.168.37.68/eteindreAlarme', []);
    
                            if ($response->getStatusCode() === 200) {      
                                // Retourner une réponse JSON indiquant que l'alarme a été éteinte avec succès
                                return new JsonResponse(['message' => 'L\'alarme a été éteinte avec succès'], 200);
                            } else {
                                // Retourner une réponse JSON en cas d'erreur
                                return new JsonResponse(['message' => 'Erreur lors de l\'envoi de la commande à l\'ESP32'], 500);
                            }                        }
                    }
                }
                if($alarme->getType() == 'pression'){
                    if($alarme->isInf() == true){
                        if($alarme->getValeur() > $donnees['pression']){
                            $response = $this->httpClient->request('GET', 'http://192.168.37.68/eteindreAlarme', []);
    
                            if ($response->getStatusCode() === 200) {      
                                // Retourner une réponse JSON indiquant que l'alarme a été éteinte avec succès
                                return new JsonResponse(['message' => 'L\'alarme a été éteinte avec succès'], 200);
                            } else {
                                // Retourner une réponse JSON en cas d'erreur
                                return new JsonResponse(['message' => 'Erreur lors de l\'envoi de la commande à l\'ESP32'], 500);
                            }                        }
                    }
                    if($alarme->isSup() == true){
                        if($alarme->getValeur() < $donnees['pression']){
                            $response = $this->httpClient->request('GET', 'http://192.168.37.68/eteindreAlarme', []);
    
                            if ($response->getStatusCode() === 200) {      
                                // Retourner une réponse JSON indiquant que l'alarme a été éteinte avec succès
                                return new JsonResponse(['message' => 'L\'alarme a été éteinte avec succès'], 200);
                            } else {
                                // Retourner une réponse JSON en cas d'erreur
                                return new JsonResponse(['message' => 'Erreur lors de l\'envoi de la commande à l\'ESP32'], 500);
                            }                        }
                    }
                }
                if($alarme->getType() == 'luminosite'){
                    if($alarme->isInf() == true){
                        if($alarme->getValeur() > $donnees['luminosite']){
                            $response = $this->httpClient->request('GET', 'http://192.168.37.68/eteindreAlarme', []);
    
                            if ($response->getStatusCode() === 200) {      
                                // Retourner une réponse JSON indiquant que l'alarme a été éteinte avec succès
                                return new JsonResponse(['message' => 'L\'alarme a été éteinte avec succès'], 200);
                            } else {
                                // Retourner une réponse JSON en cas d'erreur
                                return new JsonResponse(['message' => 'Erreur lors de l\'envoi de la commande à l\'ESP32'], 500);
                            }                        }
                    }
                    if($alarme->isSup() == true){
                        if($alarme->getValeur() < $donnees['luminosite']){
                            $response = $this->httpClient->request('GET', 'http://192.168.37.68/eteindreAlarme', []);
    
                            if ($response->getStatusCode() === 200) {      
                                // Retourner une réponse JSON indiquant que l'alarme a été éteinte avec succès
                                return new JsonResponse(['message' => 'L\'alarme a été éteinte avec succès'], 200);
                            } else {
                                // Retourner une réponse JSON en cas d'erreur
                                return new JsonResponse(['message' => 'Erreur lors de l\'envoi de la commande à l\'ESP32'], 500);
                            }                        }
                    }
                }
            }

        }
        // Répondre avec un message de succès
        return new JsonResponse(['message' => 'Mesure ajoutée avec succès'], 200);
    }

}
