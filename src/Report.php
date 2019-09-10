<?php declare(strict_types=1);

namespace Przeslijmi\CliApp;

class Report
{

    public static function info(string $text) : void
    {

        echo $text . "\n";
    }

    public static function update(string $text) : void
    {

        echo "\r" . $text;
    }

    public static function count(int $count, int $all, string $word = 'served') : void
    {

        echo "\r" . '   ' . $count . '/' . $all . ' ' . $word . ' ...';

        if ($count === $all) {
            self::updateEnd();
        }
    }

    public static function updateEnd(int $newLines = 1) : void
    {

        for ($i = 1; $i <= $newLines; ++$i) {
            echo "\n";
        }
    }
}
