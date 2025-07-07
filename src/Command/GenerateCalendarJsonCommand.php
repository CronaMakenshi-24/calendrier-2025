<?php

namespace App\Command;

use DateTime;
use DateInterval;
use DatePeriod;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:generate-calendar-json', // Définit le nom de la commande
    description: 'Génère le fichier JSON avec la liste des saints de l\'année.',
)]
class GenerateCalendarJsonCommand extends Command
{
    private const JSON_PATH = __DIR__ . '/../../public/json/calendrier_2025.json';

    protected static $defaultName = 'app:generate-calendar-json';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Charge toutes les données nécessaires
        $joursFeries = $this->getJoursFeries2025();
        $saints = $this->getSaints();
        $vacances = $this->getVacancesScolaires();

        $startDate = new DateTime("2025-01-01");
        $endDate = new DateTime("2025-12-31");
        $datePeriod = new DatePeriod($startDate, new DateInterval("P1D"), $endDate->modify("+1 day"));

        $calendrier = [];

        foreach ($datePeriod as $date) {
            $formattedDate = $date->format("Y-m-d");
            $month = $date->format("F");
            $dayKey = $date->format("d-m");


            if (!isset($calendrier[$month])) {
                $calendrier[$month] = [];
            }

            $jourData = [
                'date' => $formattedDate,
                'saints' => [],
                'jours_feries' => [],
                'vacances' => [],
                'zone' => [],
            ];


            if (isset($saints[$dayKey])) {
                $jourData['saints'][] = $saints[$dayKey];
            }


            if (isset($joursFeries[$formattedDate])) {
                $jourData['jours_feries'][] = $joursFeries[$formattedDate];
            }

            foreach ($vacances as $vacanceName => [$start, $end]) {
                if ($date >= new DateTime($start) && $date <= new DateTime($end)) {
                    $jourData['vacances'][] = "Vacances $vacanceName";

                    if (
                        str_contains($vacanceName, 'd\'Hiver Zone A') ||
                        str_contains($vacanceName, 'de Printemps Zone A')
                    ) {
                        $jourData['zone'][] = 'A'; // Ajoute 'A' au tableau
                    }
                    if (
                        str_contains($vacanceName, 'd\'Hiver Zone B') ||
                        str_contains($vacanceName, 'de Printemps Zone B')
                    ) {
                        $jourData['zone'][] = 'B'; // Ajoute 'B' au tableau
                    }
                    if (
                        str_contains($vacanceName, 'd\'Hiver Zone C') ||
                        str_contains($vacanceName, 'de Printemps Zone C')
                    ) {
                        $jourData['zone'][] = 'C'; // Ajoute 'C' au tableau
                    }
                    if (
                        in_array($vacanceName, ['de Noël début','de Noël fin', 'd\'Été'], true)
                    ) {
                        $jourData['zone'][] = 'A, B, C';
                    }
                }
            }

            // Élimine les doublons des zones pour éviter plusieurs occurrences
            $jourData['zone'] = array_unique($jourData['zone']);

            // Contrôle final pour concaténer les zones en une chaîne si nécessaire
            if (!empty($jourData['zone'])) {
                $jourData['zone'] = implode(', ', $jourData['zone']);
            } else {
                $jourData['zone'] = null; // Retourne null si aucune zone n'est trouvée
            }

            $calendrier[$month][] = $jourData;
        }

        $outputJson = json_encode(['2025' => $calendrier], JSON_PRETTY_PRINT);
        file_put_contents(self::JSON_PATH, $outputJson);

        $io->success("Le fichier JSON a été généré avec succès : " . self::JSON_PATH);
        return Command::SUCCESS;
    }

    private function getJoursFeries2025(): array
    {
        // Liste des jours fériés en France pour 2025
        return [
            "2025-01-01" => "Jour de l'an",
            "2025-04-21" => "Lundi de Pâques",
            "2025-05-01" => "Fête du Travail",
            "2025-05-08" => "Victoire 1945",
            "2025-05-29" => "Ascension",
            "2025-06-09" => "Lundi de Pentecôte",
            "2025-07-14" => "Fête nationale",
            "2025-08-15" => "Assomption",
            "2025-11-01" => "Toussaint",
            "2025-11-11" => "Armistice",
            "2025-12-25" => "Noël"
        ];
    }

    private function getSaints(): array
    {
        return [
            // JANVIER
            "01-01" => "Saint Marie",
            "02-01" => "Saint Basile",
            "03-01" => "Sainte Geneviève",
            "04-01" => "Saint Odilon",
            "05-01" => "Saint Édouard - Épiphanie",
            "06-01" => "Saint Mélaine",
            "07-01" => "Saint Raymond - 🌓",
            "08-01" => "Saint Lucien",
            "09-01" => "Sainte Alix",
            "10-01" => "Saint Guillaume",
            "11-01" => "Saint Paulin",
            "12-01" => "Sainte Tatiana",
            "13-01" => "Saint Yvette - 🌕",
            "14-01" => "Saint Nina",
            "15-01" => "Saint Rémi",
            "16-01" => "Saint Marcel",
            "17-01" => "Saint Antoine le Grand - Saint Roseline",
            "18-01" => "Sainte Prisca",
            "19-01" => "Saint Marius",
            "20-01" => "Saint Sébastien",
            "21-01" => "Sainte Agnès - 🌗",
            "22-01" => "Saint Vincent",
            "23-01" => "Saint Barnard",
            "24-01" => "Saint François de Sales",
            "25-01" => "Conversion de Saint Paul",
            "26-01" => "Saint Paule",
            "27-01" => "Sainte Angèle Mérici",
            "28-01" => "Saint Thomas d'Aquin",
            "29-01" => "Saint Gildas - 🌑",
            "30-01" => "Sainte Martine",
            "31-01" => "Saint Marcelle",
            // FÉVRIER
            "01-02" => "Sainte Ella",
            "02-02" => "Chandeleur - Présentation du Seigueur",
            "03-02" => "Saint Blaise",
            "04-02" => "Saint André Corsini - Sainte Véronique",
            "05-02" => "Sainte Agathe - 🌓",
            "06-02" => "Saint Gaston",
            "07-02" => "Sainte Eugénie",
            "08-02" => "Saint Jean de Matha - Sainte Jacqueline",
            "09-02" => "Sainte Apolline",
            "10-02" => "Sainte Scholastique - Saint Arnaud",
            "11-02" => "Notre-Dame de Lourdes",
            "12-02" => "Saint Félix - 🌕",
            "13-02" => "Sainte Béatrice",
            "14-02" => "Saint Valentin",
            "15-02" => "Saint Claude la Colombière",
            "16-02" => "Sainte Julienne",
            "17-02" => "Saint Alexis",
            "18-02" => "Sainte Bernadette",
            "19-02" => "Saint Gabin",
            "20-02" => "Saint Aimée - 🌗",
            "21-02" => "Saint Pierre Damien",
            "22-02" => "Saint Isabelle",
            "23-02" => "Saint Polycarpe - Saint Lazare",
            "24-02" => "Saint Modeste",
            "25-02" => "Saint Roméo",
            "26-02" => "Saint Nestor",
            "27-02" => "Sainte Honorine",
            "28-02" => "Saint Romain - 🌑",
            "29-02" => "Saint Auguste",
            // MARS
            "01-03" => "Saint Aubin",
            "02-03" => "Sainte Agnès de Bohème - Saint Charles le Bon",
            "03-03" => "Saint Guénolé",
            "04-03" => "Saint Casimir - Mardi Gras",
            "05-03" => "Saint Olive",
            "06-03" => "Sainte Colette - 🌓",
            "07-03" => "Sainte Félicité",
            "08-03" => "Saint Jean de Dieu",
            "09-03" => "Sainte Françoise Romaine -Carême",
            "10-03" => "Saint Vivien",
            "11-03" => "Sainte Rosine",
            "12-03" => "Saint Justine",
            "13-03" => "Sainte Euphrasie - Saint Rodrigue",
            "14-03" => "Sainte Mathilde - 🌕",
            "15-03" => "Sainte Louise de Marillac",
            "16-03" => "Sainte Bénédicte",
            "17-03" => "Saint Patrick",
            "18-03" => "Saint Cyrille",
            "19-03" => "Saint Joseph",
            "20-03" => "Saint Herbert - Printemps",
            "21-03" => "Sainte Clémence",
            "22-03" => "Sainte Léa - 🌗",
            "23-03" => "Saint Victorien",
            "24-03" => "Sainte Catherine de Suède",
            "25-03" => "Annonciation",
            "26-03" => "Saint Larissa",
            "27-03" => "Saint Habib - Mi-Carême",
            "28-03" => "Saint Gontran",
            "29-03" => "Sainte Gladys - 🌑",
            "30-03" => "Saint Amédée",
            "31-03" => "Saint Benjamin",
            // AVRIL
            "01-04" => "Saint Hugues",
            "02-04" => "Sainte Sandrine",
            "03-04" => "Saint Richard",
            "04-04" => "Sainte Isidore",
            "05-04" => "Sainte Irène - 🌓",
            "06-04" => "Saint Marcelin",
            "07-04" => "Saint Jean-Baptiste de la Salle",
            "08-04" => "Saint Jules",
            "09-04" => "Saint Gautier",
            "10-04" => "Saint Fulbert",
            "11-04" => "Saint Stanislas",
            "12-04" => "Saint Jules",
            "13-04" => "Sainte Ida - Rameaux - 🌕",
            "14-04" => "Sainte Maxime",
            "15-04" => "Sainte Anastasie - Saint Patern",
            "16-04" => "Saint Benoît-Joseph",
            "17-04" => "Saint Anicet",
            "18-04" => "Sainte Parfait",
            "19-04" => "Sainte Emma",
            "20-04" => "Sainte Odette - Pâques",
            "21-04" => "Saint Anselme - 🌗",
            "22-04" => "Sainte Alexandre",
            "23-04" => "Saint Georges",
            "24-04" => "Saint Fidèle",
            "25-04" => "Saint Marc",
            "26-04" => "Sainte Alida",
            "27-04" => "Sainte Zita - 🌑",
            "28-04" => "Sainte Valérie",
            "29-04" => "Sainte Catherine de Sienne",
            "30-04" => "Saint Robert",
            // MAI
            "01-05" => "Saint Jérémie",
            "02-05" => "Saint Athanase - Saint Boris",
            "03-05" => "Saint Philippe et Saint Jacques",
            "04-05" => "Saint Sylvain - 🌓",
            "05-05" => "Saint Judith",
            "06-05" => "Sainte Prudence",
            "07-05" => "Sainte Gisèle",
            "08-05" => "Saint Michel",
            "09-05" => "Saint Pacôme",
            "10-05" => "Saint Solange",
            "11-05" => "Sainte Estelle",
            "12-05" => "Saint Achille - 🌕",
            "13-05" => "Sainte Rolande",
            "14-05" => "Saint Matthias",
            "15-05" => "Saint Denise",
            "16-05" => "Saint Honoré",
            "17-05" => "Saint Pascal",
            "18-05" => "Saint Éric",
            "19-05" => "Saint Yves",
            "20-05" => "Saint Bernardin - 🌗",
            "21-05" => "Saint Constantin",
            "22-05" => "Sainte Émilie",
            "23-05" => "Saint Didier",
            "24-05" => "Saint Donatien",
            "25-05" => "Sainte Sophie - Fête des Mères",
            "26-05" => "Saint Bérenger",
            "27-05" => "Saint Augustin - 🌑",
            "28-05" => "Saint Germain",
            "29-05" => "Saint Ursule",
            "30-05" => "Saint Ferdinand",
            "31-05" => "Visitation de la Vierge Marie",
            // JUIN
            "01-06" => "Saint Justin",
            "02-06" => "Saint Blandine",
            "03-06" => "Saint Kévin - 🌓",
            "04-06" => "Sainte Clotilde",
            "05-06" => "Saint Igor",
            "06-06" => "Saint Norbert",
            "07-06" => "Saint Gilbert",
            "08-06" => "Saint Médard - Pentecôte",
            "09-06" => "Saint Éphrem - Saint Diane",
            "10-06" => "Saint Landry",
            "11-06" => "Saint Barnabé - 🌕",
            "12-06" => "Saint Guy",
            "13-06" => "Saint Antoine de Padoue",
            "14-06" => "Saint Élisée",
            "15-06" => "Saint Germaine -Fête des Péres",
            "16-06" => "Saint Jean-François Régis",
            "17-06" => "Saint Hervé",
            "18-06" => "Saint Léonce - 🌗",
            "19-06" => "Saint Romuald",
            "20-06" => "Saint Sylvère",
            "21-06" => "Saint Louis de Gonzague - Saint Aloïs - Saint Rodolphe - ÉTÉ - Fête de la musique",
            "22-06" => "Saint Alban - Fête de Dieu",
            "23-06" => "Saint Audrey",
            "24-06" => "Saint Jean-Baptiste",
            "25-06" => "Saint Prosper - 🌑",
            "26-06" => "Saint Anthelme",
            "27-06" => "Saint Fernand",
            "28-06" => "Saint Irénée",
            "29-06" => "Saint Pierre et Saint Paul",
            "30-06" => "Saint Martial",
            // JUILLET
            "01-07" => "Saint Thierry",
            "02-07" => "Sainte Martinien - 🌓",
            "03-07" => "Saint Thomas",
            "04-07" => "Saint Florent",
            "05-07" => "Saint Antoine-Marie Zaccaria",
            "06-07" => "Sainte Mariette Goretti",
            "07-07" => "Saint Raoul",
            "08-07" => "Saint Thibault",
            "09-07" => "Sainte Amandine",
            "10-07" => "Saint Ulrich - 🌕",
            "11-07" => "Saint Benoît",
            "12-07" => "Saint Olivier",
            "13-07" => "Saint Henri - Saint Joël",
            "14-07" => "Saint Camille de Lellis",
            "15-07" => "Saint Donald",
            "16-07" => "Notre-Dame du Mont Carmel",
            "17-07" => "Sainte Charlotte",
            "18-07" => "Saint Frédéric - 🌗",
            "19-07" => "Saint Arsène",
            "20-07" => "Sainte Marina",
            "21-07" => "Saint Victor",
            "22-07" => "Sainte Marie-Madeleine",
            "23-07" => "Sainte Brigitte",
            "24-07" => "Saint Christine - 🌑",
            "25-07" => "Saint Jacques le Majeur",
            "26-07" => "Sainte Anne - Saint Joachim",
            "27-07" => "Saint Nathalie",
            "28-07" => "Saint Samson",
            "29-07" => "Sainte Marthe",
            "30-07" => "Saint Juliette",
            "31-07" => "Saint Ignace de Loyola",
            // AOÛT
            "01-08" => "Saint Alphonse-Marie - 🌓",
            "02-08" => "Saint Julien",
            "03-08" => "Saint Lydie",
            "04-08" => "Saint Jean-Marie Vianney",
            "05-08" => "Saint Abel",
            "06-08" => "Transfiguration",
            "07-08" => "Saint Gaétan",
            "08-08" => "Saint Dominique",
            "09-08" => "Saint Amour - 🌕",
            "10-08" => "Saint Laurent",
            "11-08" => "Sainte Claire",
            "12-08" => "Sainte Clarisse",
            "13-08" => "Saint Hippolyte",
            "14-08" => "Saint Evrard",
            "15-08" => "Assomption de la Vierge Marie",
            "16-08" => "Saint Armel - 🌗",
            "17-08" => "Saint Hyacinthe",
            "18-08" => "Sainte Hélène",
            "19-08" => "Saint Jean Eudes",
            "20-08" => "Saint Bernard",
            "21-08" => "Saint Christophe",
            "22-08" => "Sainte Marie Reine - Saint Fabrice",
            "23-08" => "Sainte Rose de Lima - 🌑",
            "24-08" => "Saint Barthélémy",
            "25-08" => "Saint Louis",
            "26-08" => "Saint Césaire - Sainte Natacha",
            "27-08" => "Sainte Monique",
            "28-08" => "Saint Augustin",
            "29-08" => "Saint Jean-Baptiste (Décollation) - Saint Sabine",
            "30-08" => "Saint Fiacre",
            "31-08" => "Saint Aristide - 🌓",
            // SEPTEMBRE
            "01-09" => "Saint Gilles",
            "02-09" => "Sainte Ingrid",
            "03-09" => "Saint Grégoire le Grand",
            "04-09" => "Saint Moïse - Saint Rosalie",
            "05-09" => "Saint Laurent Justinien - Saint Raïssa",
            "06-09" => "Saint Bertrand",
            "07-09" => "Saint Cloud - Sainte Reine - 🌕",
            "08-09" => "Nativité de la Vierge Marie - Saint Adrien",
            "09-09" => "Saint Alain",
            "10-09" => "Saint Inès Takeya",
            "11-09" => "Saint Adelphe",
            "12-09" => "Saint Apollinaire",
            "13-09" => "Saint Aimé",
            "14-09" => "Saint Croix - Saint Cyprien - 🌗",
            "15-09" => "Saint Roland",
            "16-09" => "Saint Édith",
            "17-09" => "Saint Renaud",
            "18-09" => "Saint Joseph de Cupertino - Sainte Nadège",
            "19-09" => "Saint Janvier - Saint Emilie",
            "20-09" => "Saint André Kim - Saint Davy",
            "21-09" => "Saint Matthieu - 🌑",
            "22-09" => "Saint Maurice - Automne",
            "23-09" => "Saint Constant",
            "24-09" => "Sainte Thècle",
            "25-09" => "Saint Hermann",
            "26-09" => "Saint Côme et Saint Damien",
            "27-09" => "Saint Vincent de Paul",
            "28-09" => "Saint Venceslas",
            "29-09" => "Saint Michel",
            "30-09" => "Saint Jérôme - 🌓",
            // OCTOBRE
            "01-10" => "Sainte Thérèse de l'Enfant Jésus",
            "02-10" => "Saints Anges Gardiens - Saint Léger",
            "03-10" => "Saint Gérard",
            "04-10" => "Saint François d'Assise",
            "05-10" => "Sainte Fleur",
            "06-10" => "Saint Bruno",
            "07-10" => "Notre-Dame du Rosaire - Saint Serge - 🌕",
            "08-10" => "Saint Pélagie",
            "09-10" => "Saint Denis",
            "10-10" => "Saint Ghislain",
            "11-10" => "Saint Firmin",
            "12-10" => "Saint Wilfried",
            "13-10" => "Saint Géraud - 🌗",
            "14-10" => "Saint Juste",
            "15-10" => "Sainte Thérèse d'Avila",
            "16-10" => "Sainte Marguerite-Marie - Saint Edwige",
            "17-10" => "Saint Ignace d'Antioche - Saint Baudoin",
            "18-10" => "Saint Luc",
            "19-10" => "Saint René",
            "20-10" => "Sainte Adeline",
            "21-10" => "Sainte Céline - 🌑",
            "22-10" => "Saint Élodie",
            "23-10" => "Saint Jean de Capistran",
            "24-10" => "Saint Florentin",
            "25-10" => "Saint Crépin et Crépinien",
            "26-10" => "Saint Dimitri",
            "27-10" => "Saint Émeline",
            "28-10" => "Saint Simon et Saint Jude",
            "29-10" => "Saint Narcisse - 🌓",
            "30-10" => "Sainte Bienvenue",
            "31-10" => "Saint Quentin",
            // NOVEMBRE
            "01-11" => "Toussaint est la fête de tous les saints",
            "02-11" => "Commémoration des fidèles défunts",
            "03-11" => "Saint Hubert",
            "04-11" => "Saint Charles Borromée",
            "05-11" => "Saint Sylvie - 🌕",
            "06-11" => "Saint Léonard - Saint Bertille",
            "07-11" => "Saint Ernest - Sainte Carine",
            "08-11" => "Saint Geoffroy",
            "09-11" => "Saint Théodore",
            "10-11" => "Saint Léon le Grand",
            "11-11" => "Saint Martin",
            "12-11" => "Saint Christian - 🌗",
            "13-11" => "Saint Brice",
            "14-11" => "Saint Sidoine",
            "15-11" => "Saint Albert le Grand",
            "16-11" => "Sainte Marguerite d'Écosse",
            "17-11" => "Sainte Élisabeth de Hongrie",
            "18-11" => "Dédicace des basiliques de Saint Pierre et Saint Paul - Saint Pierre et Saint Paul, Saint Aube",
            "19-11" => "Saint Tanguy",
            "20-11" => "Saint Edmond - 🌑",
            "21-11" => "Présentation de Marie - Saint Dimitri",
            "22-11" => "Sainte Cécile",
            "23-11" => "Saint Clément",
            "24-11" => "Saint Chrysogone - Sainte Flora",
            "25-11" => "Sainte Catherine d'Alexandrie",
            "26-11" => "Saint Delphine",
            "27-11" => "Saint Séverin",
            "28-11" => "Saint Jacques de la Marche - 🌓",
            "29-11" => "Saint Saturnin",
            "30-11" => "Saint André",
            // DÉCEMBRE
            "01-12" => "Saint Éloi - Sainte Florence",
            "02-12" => "Sainte Viviane",
            "03-12" => "Saint François-Xavier",
            "04-12" => "Sainte Barbara",
            "05-12" => "Saint Gérald - 🌕",
            "06-12" => "Saint Nicolas",
            "07-12" => "Saint Ambroise",
            "08-12" => "Immaculée Conception",
            "09-12" => "Saint Pierre Fourier",
            "10-12" => "Saint Romaric",
            "11-12" => "Saint Daniel - 🌗",
            "12-12" => "Saint Corentin",
            "13-12" => "Sainte Lucie",
            "14-12" => "Saint Jean de la Croix - Sainte Odile",
            "15-12" => "Saint Ninon",
            "16-12" => "Sainte Adélaïde - Sainte Alice",
            "17-12" => "Saint Gaël",
            "18-12" => "Saint Gatien",
            "19-12" => "Saint Urbain",
            "20-12" => "Saint Théophile - 🌑",
            "21-12" => "Saint Pierre Canisius",
            "22-12" => "Saint Françoise-Xavière - Hiver",
            "23-12" => "Saint Armand",
            "24-12" => "Sainte Adèle",
            "25-12" => "Nativité de Jésus",
            "26-12" => "Saint Étienne",
            "27-12" => "Saint Jean l'Évangéliste - 🌓",
            "28-12" => "Saints Innocents",
            "29-12" => "Saint Thomas Becket - Saint David",
            "30-12" => "Saint Roger",
            "31-12" => "Saint Sylvestre",
        ];
    }


    private function getVacancesScolaires(): array
    {
        return [
            "de Noël fin" => ["2024-12-21", "2025-01-06"],
            "de Noël début" => ["2025-12-20", "2026-01-04"],
            "d'Hiver Zone A" => ["2025-02-08", "2025-02-23"],
            "d'Hiver Zone B" => ["2025-02-15", "2025-03-02"],
            "d'Hiver Zone C" => ["2025-02-22", "2025-03-09"],
            "de Printemps Zone A" => ["2025-04-05", "2025-04-21"],
            "de Printemps Zone B" => ["2025-04-12", "2025-04-28"],
            "de Printemps Zone C" => ["2025-04-19", "2025-05-05"],
            "d'Été" => ["2025-07-05", "2025-09-01"]
        ];
    }

}