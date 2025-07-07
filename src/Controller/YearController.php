<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use IntlDateFormatter;

class YearController extends AbstractController
{
    #[Route('/', name: 'app_year')]
    public function index(): Response
    {
        $filePath = __DIR__ . '/../../public/json/calendrier_2025.json';

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException("Le fichier JSON n'existe pas.");
        }

        $jsonStr = file_get_contents($filePath);
        $jsonData = json_decode($jsonStr, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Erreur dans le décodage du fichier JSON.");
        }

        if (!isset($jsonData['2025'])) {
            throw $this->createNotFoundException("Données pour l'année 2025 introuvables.");
        }

        $yearData = $jsonData['2025'];
        $formattedYearData = $this->formatDates($yearData);

        return $this->render('year/index.html.twig', [
            'controller_name' => 'YearController',
            'year_data' => $formattedYearData,
        ]);

    }

    private function formatDates(array $yearData): array
    {
        foreach ($yearData as $month => $days) {
            foreach ($days as &$day) {
                // Vérifie si "date" existe dans les données
                if (isset($day['date'])) {
                    $date = new \DateTime($day['date']);
                    $formatter = new \IntlDateFormatter(
                        'fr_FR',
                        \IntlDateFormatter::FULL,
                        \IntlDateFormatter::NONE
                    );

                    // Ajoute la clé "formatted_date" avec la date formatée
                    $day['formatted_date'] = $formatter->format($date);
                } else {
                    // Si "date" n'existe pas, assigne une valeur par défaut
                    $day['formatted_date'] = 'Date inconnue';
                }
            }
        }

        return $yearData;
    }
}