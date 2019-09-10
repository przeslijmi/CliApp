<?php declare(strict_types=1);

namespace Przeslijmi\CliApp;

class Ask
{

    public static function ask(string $question) : string
    {

        echo trim($question) . ' ';

        $handle = fopen ('php://stdin','rb');
        $line = fgets($handle);

        return $line;
    }

    public static function secretly(string $question) : string
    {

        echo trim($question) . ' ';

        self::hide();
        $handle = fopen ('php://stdin','rb');
        $line = fgets($handle);
        self::show();

        return $line;
    }

    private static function hide() : void
    {

        // if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
        echo "\033[30;40m";
        flush();
        // }
    }

    private static function show() : void
    {

        // if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
        echo "\033[0m";
        flush();
        // }
    }
}
