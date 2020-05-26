<?php declare(strict_types=1);

namespace Przeslijmi\CliApp;

use PHPUnit\Framework\TestCase;
use Przeslijmi\CliApp\Example\ConfigsApp;
use Przeslijmi\CliApp\Example\CorruptedApp;
use Przeslijmi\CliApp\Example\HandlersApp;
use Przeslijmi\CliApp\Example\SimpleApp;
use Przeslijmi\CliApp\Exceptions\CliAppFopException;
use Przeslijmi\CliApp\Exceptions\ParamDonoexException;
use Przeslijmi\CliApp\Params;

/**
 * Methods for testing File class.
 */
final class CliAppTest extends TestCase
{

    /**
     * Test if SimpleApp is working.
     *
     * @return void
     */
    public function testIfSimpleAppWorks() : void
    {

        // Create application.
        $app = new SimpleApp();
        $app->getParams()->setOperation('cook');

        // Prepare.
        $this->expectOutputString('cooking...');

        // Start.
        $app->start();

        // Test.
        $this->assertTrue(is_a($app->getParams(), Params::class));
        $this->assertTrue(is_array($app->getParams()->get()));
        $this->assertEquals(1, count($app->getParams()->get()));
        $this->assertEquals([ 'config' ], array_keys($app->getParams()->get()));
        $this->assertEquals('cook', $app->getParams()->getOperation());
        $this->assertTrue(empty($app->getParams()->getParam('config')));
        $this->assertEquals(null, $app->getParams()->getParam('missing', false));
    }

    /**
     * Test if SimpleApp with multiple extra params is working.
     *
     * @return void
     */
    public function testIfSimpleAppWithMultipleParamsWorks() : void
    {

        // Create application.
        $app = new SimpleApp();
        $app->getParams()->set([
            'file.php',
            'cook',
            '-param1',
            'param1Value',
            '--param2',
            'param2Value'
        ]);

        // Prepare.
        $this->expectOutputString('cooking...');

        // Start.
        $app->start();

        // Test.
        $this->assertTrue(is_a($app->getParams(), Params::class));
        $this->assertTrue(is_array($app->getParams()->get()));
        $this->assertEquals(3, count($app->getParams()->get()));
        $this->assertEquals(
            [ '', 'param1', 'param2' ],
            array_keys($app->getParams()->get())
        );
        $this->assertEquals('cook', $app->getParams()->getOperation());
        $this->assertEquals('param1Value', $app->getParams()->getParam('param1'));
        $this->assertEquals('param2Value', $app->getParams()->getParam('param2'));
        $this->assertEquals(null, $app->getParams()->getParam('missing', false));
    }

    /**
     * Test if SimpleApp with multiple extra params is working.
     *
     * @return void
     */
    public function testIfSimpleAppWithStartedBySetWorks() : void
    {

        // Create application.
        $app = new SimpleApp();
        $app->getParams()->set([ 'file.php' ]);
        $app->getParams()->setOperation('cook');

        // Prepare.
        $this->expectOutputString('cooking...');

        // Start.
        $app->start();

        // Test.
        $this->assertTrue(is_a($app->getParams(), Params::class));
        $this->assertTrue(is_array($app->getParams()->get()));
        $this->assertEquals(0, count($app->getParams()->get()));
        $this->assertTrue(empty(array_keys($app->getParams()->get())));
        $this->assertEquals('cook', $app->getParams()->getOperation());
        $this->assertEquals(null, $app->getParams()->getParam('missing', false));
    }

    /**
     * Test if HandlersApp is working.
     *
     * @return void
     */
    public function testIfHandlersAppWorks() : void
    {

        // Create application.
        $app = new HandlersApp();
        $app->getParams()->setOperation('cook');

        // Prepare.
        $this->expectOutputString('cooking...');

        // Start.
        $app->start();

        // Test.
        $this->assertTrue(is_a($app->getParams(), Params::class));
        $this->assertTrue(is_array($app->getParams()->get()));
        $this->assertEquals(1, count($app->getParams()->get()));
        $this->assertEquals([ 'config' ], array_keys($app->getParams()->get()));
        $this->assertEquals('cook', $app->getParams()->getOperation());
        $this->assertTrue(empty($app->getParams()->getParam('config')));
        $this->assertEquals(null, $app->getParams()->getParam('missing', false));
        $this->assertTrue($app->handledBefore);
        $this->assertTrue($app->handledAfter);
    }

    /**
     * Test if ConfigsApp is working.
     *
     * @return void
     */
    public function testIfConfigsAppWorks() : void
    {

        // Lvd.
        $dir = str_replace('\\', '/', dirname(__DIR__)) . '/';

        // Create application.
        $app = new ConfigsApp();
        $app->getParams()->setOperation('cook');
        $app->getParams()->setParam('c', $dir . 'resources/configsApp.config.php');

        // Prepare.
        $this->expectOutputString('cooking...');

        // Start.
        $app->start();

        // Test.
        $this->assertTrue(is_a($app->getParams(), Params::class));
        $this->assertTrue(is_array($app->getParams()->get()));
        $this->assertEquals(4, count($app->getParams()->get()));
        $this->assertEquals(
            [ 'config', 'c', 'scf', 'setInConfigs' ],
            array_keys($app->getParams()->get())
        );
        $this->assertEquals(
            $app->getParams()->getParam('config'),
            $app->getParams()->getParam('c')
        );
        $this->assertEquals(null, $app->getParams()->getParam('missing', false));
        $this->assertEquals('cook', $app->getParams()->getOperation());
        $this->assertEquals('yeah', $app->getParams()->getParam('setInConfigs'));
        $this->assertEquals('yeah', $app->getParams()->getParam('scf'));
    }

    /**
     * Test if getting nonexisting param throws.
     *
     * @return void
     */
    public function testIfGettingNonexistingParamThrows() : void
    {

        // Create application.
        $app = new SimpleApp();
        $app->getParams()->setOperation('cook');

        // Prepare.
        $this->expectException(ParamDonoexException::class);

        // Test.
        $app->getParams()->getParam('missing');
    }

    /**
     * Test if starting help operation works.
     *
     * @return void
     */
    public function testIfStartingHelpOperationWorks() : void
    {

        // Create application.
        $app = new SimpleApp();
        $app->getParams()->setOperation('?');

        // Test.
        $this->assertEquals('help', $app->getParams()->getOperation());
    }

    /**
     * Test if missing configurations throws.
     *
     * @return void
     */
    public function testIfMissingConfigurationsThrows() : void
    {

        // Create application.
        $app = new ConfigsApp();
        $app->getParams()->setOperation('cook');
        $app->getParams()->setParam('c', 'nonexstingFile.xxx');

        // Prepare.
        $this->expectException(CliAppFopException::class);

        // Start.
        $app->start();
    }

    /**
     * Test if missing operation throws.
     *
     * @return void
     */
    public function testIfMissingOperationThrows() : void
    {

        // Create application.
        $app = new ConfigsApp();
        $app->getParams()->setOperation('nonexstingOperation');

        // Prepare.
        $this->expectException(CliAppFopException::class);

        // Start.
        $app->start();
    }

    /**
     * Test if corrupted config file throws.
     *
     * @return void
     */
    public function testIfConfigsAppWithCorruptedConfigFileThrows() : void
    {

        // Lvd.
        $dir = str_replace('\\', '/', dirname(__DIR__)) . '/';

        // Create application.
        $app = new ConfigsApp();
        $app->getParams()->setOperation('cook');
        $app->getParams()->setParam('c', $dir . 'resources/corrupted.config.php');

        // Prepare.
        $this->expectException(CliAppFopException::class);

        // Start.
        $app->start();
    }

    /**
     * Test if corrupted operation in app file throws.
     *
     * @return void
     */
    public function testIfCorruptedAppWithCorruptedOperationThrows() : void
    {

        // Create application.
        $app = new CorruptedApp();
        $app->getParams()->setOperation('cook');

        // Prepare.
        $this->expectException(CliAppFopException::class);

        // Start.
        $app->start();
    }

    /**
     * Test if logging works.
     *
     * @return void
     */
    public function testIfLoggingWorks() : void
    {

        // Create application.
        $app   = new SimpleApp();
        $color = constant('\Przeslijmi\Silogger\Usage\CliUsage::DEBUG_COLOR');

        // What to expect.
        $expect  = "\e[" . $color . 'mLOG[default] debug    : ';
        $expect .= 'Przeslijmi\CliApp\Example\SimpleApp test message' . "\e[0m" . PHP_EOL;
        $this->expectOutputString($expect);

        // Test.
        $app->deleteLog();
        $app->setLogName('test1');
        $app->log('debug', 'test message');
    }

    /**
     * Test if logging counter works.
     *
     * @return void
     */
    public function testIfLoggingCounterWorks() : void
    {

        // Create application.
        $app   = new SimpleApp();
        $color = constant('\Przeslijmi\Silogger\Usage\CliUsage::DEBUG_COLOR');

        // What to expect.
        $expect = "\e[" . $color . 'mLOG[default] debug    : served: 1 / 2' . "\e[0m\r";
        $this->expectOutputString($expect);

        // Test.
        $app->deleteLog();
        $app->setLogName('test2');
        $app->logCounter('debug', 1, 2, 'served');
    }
}
