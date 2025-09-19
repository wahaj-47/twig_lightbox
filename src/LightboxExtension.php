<?php

namespace Drupal\twig_lightbox\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class LightboxExtension extends AbstractExtension
{

    public function getFilters()
    {
        return [
            new TwigFilter('lightbox', [$this, 'lightbox'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Wrap all <img> tags with an <a> tag for lightbox support.
     *
     * @param string $html
     *   The HTML string containing <img> tags.
     * @param string $group
     *   The value for the data-lightbox attribute (group name).
     *
     * @return string
     *   Modified HTML with <a> wrappers.
     */
    public function lightbox($html, $group)
    {
        dpm($html, $group);
    }
}
