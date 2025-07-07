<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SeptemberController extends AbstractController
{
    #[Route('/september', name: 'app_september')]
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

        $septemberData = $jsonData['2025']['September'] ?? null;

        return $this->render('september/index.html.twig', [
            'controller_name' => 'SeptemberController',
            'month_data' => $septemberData,
        ]);
    }
}
