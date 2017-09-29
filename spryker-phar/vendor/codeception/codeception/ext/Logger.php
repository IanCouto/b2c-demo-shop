<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Codeception\Extension;

use Codeception\Event\FailEvent;
use Codeception\Event\StepEvent;
use Codeception\Event\SuiteEvent;
use Codeception\Event\TestEvent;
use Codeception\Events;
use Codeception\Exception\ConfigurationException;
use Codeception\Extension;
use Codeception\Test\Descriptor;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger as MonologLogger;

/**
 * Log suites/tests/steps using Monolog library.
 * Monolog should be installed additionally by Composer.
 *
 * ```
 * composer require monolog/monolog
 * ```
 *
 * Steps are logged into `tests/_output/codeception.log`
 *
 * To enable this module add to your `codeception.yml`:
 *
 * ``` yaml
 * extensions:
 *     enabled: [Codeception\Extension\Logger]
 * ```
 *
 * #### Config
 *
 * * `max_files` (default: 3) - how many log files to keep
 *
 */
class Logger extends Extension
{

    public static $events = [
        Events::SUITE_BEFORE => 'beforeSuite',
        Events::TEST_BEFORE => 'beforeTest',
        Events::TEST_AFTER => 'afterTest',
        Events::TEST_END => 'endTest',
        Events::STEP_BEFORE => 'beforeStep',
        Events::TEST_FAIL => 'testFail',
        Events::TEST_ERROR => 'testError',
        Events::TEST_INCOMPLETE => 'testIncomplete',
        Events::TEST_SKIPPED => 'testSkipped',
    ];

    protected $logHandler;

    /**
     * @var \Monolog\Logger
     */
    protected $logger;

    protected $path;

    protected $config = ['max_files' => 3];

    /**
     * @throws \Codeception\Exception\ConfigurationException
     *
     * @return void
     */
    public function _initialize()
    {
        if (!class_exists('\Monolog\Logger')) {
            throw new ConfigurationException("Logger extension requires Monolog library to be installed");
        }
        $this->path = $this->getLogDir();

        // internal log
        $logHandler = new RotatingFileHandler($this->path . 'codeception.log', $this->config['max_files']);
        $this->logger = new MonologLogger('Codeception');
        $this->logger->pushHandler($logHandler);
    }

    /**
     * @return void
     */
    public function beforeSuite(SuiteEvent $e)
    {
        $suite = str_replace('\\', '_', $e->getSuite()->getName());
        $this->logHandler = new RotatingFileHandler($this->path . $suite, $this->config['max_files']);
    }

    /**
     * @return void
     */
    public function beforeTest(TestEvent $e)
    {
        $this->logger = new MonologLogger(Descriptor::getTestFileName($e->getTest()));
        $this->logger->pushHandler($this->logHandler);
        $this->logger->info('------------------------------------');
        $this->logger->info("STARTED: " . ucfirst(Descriptor::getTestAsString($e->getTest())));
    }

    /**
     * @return void
     */
    public function afterTest(TestEvent $e)
    {
    }

    /**
     * @return void
     */
    public function endTest(TestEvent $e)
    {
        $this->logger->info("PASSED");
    }

    /**
     * @return void
     */
    public function testFail(FailEvent $e)
    {
        $this->logger->alert($e->getFail()->getMessage());
        $this->logger->info("# FAILED #");
    }

    /**
     * @return void
     */
    public function testError(FailEvent $e)
    {
        $this->logger->alert($e->getFail()->getMessage());
        $this->logger->info("# ERROR #");
    }

    /**
     * @return void
     */
    public function testSkipped(FailEvent $e)
    {
        $this->logger->info("# Skipped #");
    }

    /**
     * @return void
     */
    public function testIncomplete(FailEvent $e)
    {
        $this->logger->info("# Incomplete #");
    }

    /**
     * @return void
     */
    public function beforeStep(StepEvent $e)
    {
        $this->logger->info((string)$e->getStep());
    }

}
