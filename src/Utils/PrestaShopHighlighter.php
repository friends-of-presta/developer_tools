<?php

namespace FOP\DeveloperTools\Utils;

final class PrestaShopHighlighter
{
    const PURPLE = '#6f42c1';
    const LIGHT_PURPLE = '#9b8af0';
    const BLUE = '#34219e';
    const LIGHT_GREY = '#9e9e9e';
    const RED = '#d73a49';
    const DARK_GREY = '#4a4a4a';
    const PINK = '#ff0076';

    private static $isEnabled = false;

    public static function enable()
    {
        if (!self::$isEnabled) {
            self::$isEnabled = true;

            ini_set("highlight.comment", self::LIGHT_GREY);
            ini_set("highlight.default", self::DARK_GREY);
            ini_set("highlight.html", self::LIGHT_PURPLE);
            ini_set("highlight.keyword", self::PURPLE."; font-weight: bold");
            ini_set("highlight.string", self::PINK);
        }
    }
}
