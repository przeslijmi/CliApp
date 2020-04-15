<?php declare(strict_types=1);

namespace Przeslijmi\CliApp;

use PHPUnit\Framework\TestCase;
use Przeslijmi\CliApp\Report;

/**
 * Methods for testing Report class.
 */
final class ReportTest extends TestCase
{

    /**
     * Test info method.
     *
     * @return void
     */
    public function testInfo() : void
    {

        // Prepare.
        $this->expectOutputString('text' . "\n");

        // Test.
        Report::info('text');
    }

    /**
     * Test update method.
     *
     * @return void
     */
    public function testUpdate() : void
    {

        // Prepare.
        $this->expectOutputString("\r" . 'text');

        // Test.
        Report::update('text');
    }

    /**
     * Test count method.
     *
     * @return void
     */
    public function testCount() : void
    {

        // Prepare.
        $this->expectOutputString("\r" . '   5/5 served ...' . "\n");

        // Test.
        Report::count(5, 5);
    }
}
