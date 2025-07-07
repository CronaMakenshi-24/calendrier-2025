<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MayController extends AbstractController
{
    #[Route('/may', name: 'app_may')]
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

        $mayData = $jsonData['2025']['May'] ?? null;

        return $this->render('may/index.html.twig', [
            'controller_name' => 'MayController',
            'month_data' => $mayData,
        ]);
    }
}
