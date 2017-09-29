<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Codeception\Coverage\Subscriber;

use Codeception\Coverage\SuiteSubscriber;
use Codeception\Event\SuiteEvent;
use Codeception\Events;

/**
 * Collects code coverage from unit and functional tests.
 * Results from all suites are merged.
 */
class Local extends SuiteSubscriber
{

    public static $events = [
        Events::SUITE_BEFORE => 'beforeSuite',
        Events::SUITE_AFTER => 'afterSuite',
    ];

    /**
     * @var \Codeception\Lib\Interfaces\Remote
     */
    protected $module;

    protected function isEnabled()
    {
        return $this->module === null and $this->settings['enabled'];
    }

    /**
     * @return void
     */
    public function beforeSuite(SuiteEvent $e)
    {
        $this->applySettings($e->getSettings());
        $this->module = $this->getServerConnectionModule($e->getSuite()->getModules());
        if (!$this->isEnabled()) {
            return;
        }
        $this->applyFilter($e->getResult());
    }

    /**
     * @return void
     */
    public function afterSuite(SuiteEvent $e)
    {
        if (!$this->isEnabled()) {
            return;
        }
        $this->mergeToPrint($e->getResult()->getCodeCoverage());
    }

}
