<?php

namespace Drupal\twig_lightbox\Twig;

use DOMDocument;
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

    public function lightbox($source, $group)
    {
        $source['#post_render'][] = function ($source, $element) use ($group) {
            return $this->wrap($source, $group);
        };
        return $source;
    }

    public function wrap($source, $group)
    {
        $html = new DOMDocument();
        $html->loadHTML($source, LIBXML_HTML_NOIMPLIED);
        $images = $html->getElementsByTagName('img');

        foreach ($images as $img) {
            $src = $img->getAttribute('src');
            if (empty($src)) continue;
            $caption = $this->getImageCaption($img);

            $anchor = $html->createElement("a");
            $anchor->setAttribute('href', $src);
            $anchor->setAttribute('data-lightbox', $group);
            $anchor->setAttribute('data-title', $caption);

            $copy = $img->cloneNode(true);
            $anchor->appendChild($copy);

            $img->parentNode->replaceChild($anchor, $img);
        }

        $source = $html->saveHTML();
        return $source;
    }

    private function getImageCaption(\DOMElement $img)
    {
        $data_title = $img->getAttribute("data-title");
        if (!empty($data_title)) return $data_title;

        $sibling = $img->nextSibling;
        while ($sibling) {
            if ($sibling->nodeName == 'figcaption') return trim($sibling->textContent);
            $sibling = $sibling->nextSibling;
        }

        $sibling = $img->previousSibling;
        while ($sibling) {
            if ($sibling->nodeName == 'figcaption') return trim($sibling->textContent);
            $sibling = $sibling->previousSibling;
        }

        return "";
    }
}
