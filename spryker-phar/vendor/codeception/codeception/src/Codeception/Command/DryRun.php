<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Codeception\Command;

use Codeception\Configuration;
use Codeception\Event\SuiteEvent;
use Codeception\Event\TestEvent;
use Codeception\Events;
use Codeception\Subscriber\Bootstrap as BootstrapLoader;
use Codeception\Subscriber\Console as ConsolePrinter;
use Codeception\SuiteManager;
use Codeception\Test\Interfaces\ScenarioDriven;
use Codeception\Test\Test;
use Codeception\Util\Maybe;
use Exception;
use InvalidArgumentException;
use PHPUnit_Framework_TestSuite_DataProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Shows step by step execution process for scenario driven tests without actually running them.
 *
 * * `codecept dry-run acceptance`
 * * `codecept dry-run acceptance MyCest`
 * * `codecept dry-run acceptance checkout.feature`
 * * `codecept dry-run tests/acceptance/MyCest.php`
 *
 */
class DryRun extends Command
{

    use Shared\Config;
    use Shared\Style;

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setDefinition(
            [
                new InputArgument('suite', InputArgument::REQUIRED, 'suite to scan for feature files'),
                new InputArgument('test', InputArgument::OPTIONAL, 'tests to be loaded'),
            ]
        );
        parent::configure();
    }

    public function getDescription()
    {
        return 'Prints step-by-step scenario-driven test or a feature';
    }

    /**
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->addStyles($output);
        $suite = $input->getArgument('suite');
        $test = $input->getArgument('test');

        $config = $this->getGlobalConfig();
        if (!Configuration::isEmpty() && !$test && strpos($suite, $config['paths']['tests']) === 0) {
            list(, $suite, $test) = $this->matchTestFromFilename($suite, $config['paths']['tests']);
        }
        $settings = $this->getSuiteConfig($suite);

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new ConsolePrinter([
            'colors' => !$input->getOption('no-ansi'),
            'steps' => true,
            'verbosity' => OutputInterface::VERBOSITY_VERBOSE,
        ]));
        $dispatcher->addSubscriber(new BootstrapLoader());

        $suiteManager = new SuiteManager($dispatcher, $suite, $settings);
        $moduleContainer = $suiteManager->getModuleContainer();
        foreach (Configuration::modules($settings) as $module) {
            $moduleContainer->mock($module, new Maybe());
        }
        $suiteManager->loadTests($test);
        $tests = $suiteManager->getSuite()->tests();

        $dispatcher->dispatch(Events::SUITE_INIT, new SuiteEvent($suiteManager->getSuite(), null, $settings));
        $dispatcher->dispatch(Events::SUITE_BEFORE, new SuiteEvent($suiteManager->getSuite(), null, $settings));
        foreach ($tests as $test) {
            if ($test instanceof PHPUnit_Framework_TestSuite_DataProvider) {
                foreach ($test as $t) {
                    if ($t instanceof Test) {
                        $this->dryRunTest($output, $dispatcher, $t);
                    }
                }
            }
            if ($test instanceof Test and $test instanceof ScenarioDriven) {
                $this->dryRunTest($output, $dispatcher, $test);
            }
        }
        $dispatcher->dispatch(Events::SUITE_AFTER, new SuiteEvent($suiteManager->getSuite()));
    }

    protected function matchTestFromFilename($filename, $tests_path)
    {
        $filename = str_replace(['//', '\/', '\\'], '/', $filename);
        $res = preg_match("~^$tests_path/(.*?)/(.*)$~", $filename, $matches);
        if (!$res) {
            throw new InvalidArgumentException("Test file can't be matched");
        }

        return $matches;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param $dispatcher
     * @param $test
     *
     * @return void
     */
    protected function dryRunTest(OutputInterface $output, EventDispatcher $dispatcher, Test $test)
    {
        $dispatcher->dispatch(Events::TEST_START, new TestEvent($test));
        $dispatcher->dispatch(Events::TEST_BEFORE, new TestEvent($test));
        try {
            $test->test();
        } catch (Exception $e) {
        }
        $dispatcher->dispatch(Events::TEST_AFTER, new TestEvent($test));
        $dispatcher->dispatch(Events::TEST_END, new TestEvent($test));
        if ($test->getMetadata()->isBlocked()) {
            $output->writeln('');
            if ($skip = $test->getMetadata()->getSkip()) {
                $output->writeln("<warning> SKIPPED </warning>" . $skip);
            }
            if ($incomplete = $test->getMetadata()->getIncomplete()) {
                $output->writeln("<warning> INCOMPLETE </warning>" . $incomplete);
            }
        }
        $output->writeln('');
    }

}
