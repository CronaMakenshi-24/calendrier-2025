<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DecemberController extends AbstractController
{
    #[Route('/december', name: 'app_december')]
    public function index(): Response
    {
        $filePath = __DIR__ . '/../../public/json/calendrier_2025.json';

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException("Le fichier JSON n'existe pas.");
        }

        $jsonData = json_decode(file_get_contents($filePath), true);

        if (!isset($jsonData['2025'])) {
            throw $this->createNotFoundException("Données pour l'année 2025 introuvables.");
        }

        $decemberData = $jsonData['2025']['December'] ?? null;

        return $this->render('december/index.html.twig', [
            'controller_name' => 'DecemberController',
            'month_data' => $decemberData,
        ]);
    }
}
