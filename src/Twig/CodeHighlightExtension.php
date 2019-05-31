<?php

namespace FOP\DeveloperTools\Twig;

use Twig\Extension\AbstractExtension;
use Symfony\Component\DependencyInjection\Container;
use FOP\DeveloperTools\Utils\PrestaShopHighlighter;
use Twig\TwigFilter;

class CodeHighlightExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('highlight', [$this, 'highlight']),
            new TwigFilter('camelize', [$this, 'camelize'])
        ];
    }

    public function highlight($code)
    {
        PrestaShopHighlighter::enable();
        return highlight_string($code, true);
    }

    public function camelize($hookName)
    {
        return Container::camelize($hookName);
    }
}
