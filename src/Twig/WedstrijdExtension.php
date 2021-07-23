<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class WedstrijdExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('isWinnaar', [$this, 'isWinnaar']),
        ];
    }

    public function getFunctions(): array
    {
        return [
        ];
    }

    public function isWinnaar($value, $object)
    {
        if ($value === $object->getWinnaar()) {
            return '<strong>' . $value->getNaam() . '</strong>';
        }

        return $value->getNaam();
    }
}
