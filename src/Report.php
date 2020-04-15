<?php declare(strict_types=1);

namespace Przeslijmi\CliApp;

/**
 * Shows comments to CLI.
 *
 * ## Counter example
 * ```
 * Report::count(0, 100, 'done');
 *
 * for ($i = 1; $i <= 100; ++$i) {
 *     Report::count($i, 100, 'done');
 * }
 * ```
 */
class Report
{

    /**
     * Shows line with given text.
     *
     * @param string $text Text to be shown.
     *
     * @return void
     */
    public static function info(string $text) : void
    {

        echo $text . "\n";
    }

    /**
     * Shows given text prepending with carriage return (deleting anything since last new line).
     *
     * @param string $text Text to be shown.
     *
     * @return void
     */
    public static function update(string $text) : void
    {

        echo "\r" . $text;
    }

    /**
     * Shows counter.
     *
     * @param integer $count Number to be shown.
     * @param integer $final Final (last) number that means end of calculation.
     * @param string  $word  Optional, 'served'. Subject of counting.
     *
     * @return void
     */
    public static function count(int $count, int $final, string $word = 'served') : void
    {

        echo "\r" . '   ' . $count . '/' . $final . ' ' . $word . ' ...';

        // Call to finish counting if final number has been reached.
        if ($count === $final) {
            self::updateEnd();
        }
    }

    /**
     * Adds new line to CLI to make updating impossible.
     *
     * @param integer $newLines Optional, 1. How many new lines to add.
     *
     * @return void
     */
    public static function updateEnd(int $newLines = 1) : void
    {

        echo str_repeat("\n", $newLines);
    }
}
