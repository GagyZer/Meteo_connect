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
    #[Route('/ledActivation', name: 'app_led')]
    public function controlLed(Request $request): Response
    {
        // $action = $request->request->get('action');

        // if ($action !== 'on' && $action !== 'off') {
        //     $this->addFlash('error', 'Action invalide');
        // }
        
        // $response = $this->httpClient->request('GET', 'http://192.168.37.68/led/on', [
        //     'query' => [
        //         'action' => $action,
        //     ],
        // ]);
        
        // if ($response->getStatusCode() === 200) {      
        //     $this->addFlash('success', 'Commande envoyée avec succès');
        // } else {
        //     $this->addFlash('error', 'Erreur lors de l\'envoi de la commande à l\'ESP32');
        // }

        return $this->render('led/ledOn.html.twig', [
            'controller_name' => 'LedController',
        ]);
    }

    #[Route('/led/on', name: 'led_on')]
    public function ledOn(Request $request): Response
    {
        $action = $request->query->get('action');
        return $this->render('led/ledOn.html.twig', [
            'controller_name' => 'LedController',
        ]);
        // Vérifier que l'action est "on"
        if ($action === 'on') {
            // Effectuer les opérations pour allumer la LED
            // Par exemple : $led->turnOn();

            // Répondre avec un message de succès
            return new Response('LED allumée');
        } else {
            // Répondre avec un message d'erreur
            return new Response('Action invalide', 400);
        }
    }

    #[Route('/led/off', name: 'lef_off')]
    public function ledOff(Request $request): Response
    {
        $action = $request->query->get('action');

        // Vérifier que l'action est "on"
        if ($action === 'on') {
            // Effectuer les opérations pour allumer la LED
            // Par exemple : $led->turnOn();

            // Répondre avec un message de succès
            return new Response('LED allumée');
        } else {
            // Répondre avec un message d'erreur
            return new Response('Action invalide', 400);
        }
    }
}
