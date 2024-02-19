<?php

namespace FlamePHPDev\FlameQuery;

class Utils {
    public static function string_between(string $string, string $start, string $end): string {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}