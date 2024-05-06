<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LedController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/eteindreAlarme', name: 'eteindreAlarme')]
    public function eteindreAlarme(Request $request): JsonResponse
    {
        $response = $this->httpClient->request('GET', 'http://192.168.37.68/eteindreAlarme', []);
    
        if ($response->getStatusCode() === 200) {      
            // Retourner une réponse JSON indiquant que l'alarme a été éteinte avec succès
            return new JsonResponse(['message' => 'L\'alarme a été éteinte avec succès'], 200);
        } else {
            // Retourner une réponse JSON en cas d'erreur
            return new JsonResponse(['message' => 'Erreur lors de l\'envoi de la commande à l\'ESP32'], 500);
        }
    }
}
