<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Codeception\Lib;

use Codeception\Actor;
use Codeception\Exception\TestRuntimeException;
use Interfaces\MultiSession;

class Friend
{

    protected $name;

    protected $actor;

    protected $data = [];

    protected $multiSessionModules = [];

    public function __construct($name, Actor $actor, $modules = [])
    {
        $this->name = $name;
        $this->actor = $actor;

        $this->multiSessionModules = array_filter($modules, function ($m) {
            return $m instanceof MultiSession;
        });

        if (empty($this->multiSessionModules)) {
            throw new TestRuntimeException("No multisession modules used. Can't instantiate friend");
        }
    }

    public function does($closure)
    {
        $currentUserData = [];

        foreach ($this->multiSessionModules as $module) {
            $name = $module->_getName();
            $currentUserData[$name] = $module->_backupSession();
            if (empty($this->data[$name])) {
                $module->_initializeSession();
                $this->data[$name] = $module->_backupSession();
                continue;
            }
            $module->_loadSession($this->data[$name]);
        };

        $this->actor->comment(strtoupper("{$this->name} does ---"));
        $ret = $closure($this->actor);
        $this->actor->comment(strtoupper("--- {$this->name} finished"));

        foreach ($this->multiSessionModules as $module) {
            $name = $module->_getName();
            $this->data[$name] = $module->_backupSession();
            $module->_loadSession($currentUserData[$name]);
        };
        return $ret;
    }

    /**
     * @return void
     */
    public function isGoingTo($argumentation)
    {
        $this->actor->amGoingTo($argumentation);
    }

    /**
     * @return void
     */
    public function expects($prediction)
    {
        $this->actor->expect($prediction);
    }

    /**
     * @return void
     */
    public function expectsTo($prediction)
    {
        $this->actor->expectTo($prediction);
    }

    /**
     * @return void
     */
    public function leave()
    {
        foreach ($this->multiSessionModules as $module) {
            if (isset($this->data[$module->_getName()])) {
                $module->_closeSession($this->data[$module->_getName()]);
            }
        }
    }

}
