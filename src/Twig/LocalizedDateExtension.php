<?php

namespace App\Twig;

use DateTime;
use \IntlDateFormatter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class LocalizedDateExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('localized_date', [$this, 'formatLocalizedDate']),
        ];
    }

    public function formatLocalizedDate(string $date, string $locale = 'fr_FR'): string
    {
        $dateTime = new DateTime($date);
        $formatter = new IntlDateFormatter(
            $locale,
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE
        );

        return $formatter->format($dateTime);
    }
}