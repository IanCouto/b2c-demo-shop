<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Codeception\Lib\Connector\ZF2;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class PersistentServiceManager extends ServiceManager implements ServiceLocatorInterface
{

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface Used to retrieve Doctrine services
     */
    private $serviceManager;

    public function __construct(ServiceLocatorInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function get($name)
    {
        if (parent::has($name)) {
            return parent::get($name);
        }
        return $this->serviceManager->get($name);
    }

    public function has($name)
    {
        if (parent::has($name)) {
            return true;
        }
        if (preg_match('/doctrine/i', $name)) {
            return $this->serviceManager->has($name);
        }
        return false;
    }

    /**
     * @return void
     */
    public function setService($name, $service)
    {
        parent::setService($name, $service);
    }

}
