<?php

namespace Pyz\Yves\CmsContentWidgetAntelopeConnector;

use Pyz\Client\Antelope\AntelopeClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class CmsContentWidgetAntelopeConnectorFactory extends AbstractFactory
{
/**
     * @return \Pyz\Client\Antelope\AntelopeClientInterface
     */
    public function getAntelopeClient(): AntelopeClientInterface
    {
        return $this->getProvidedDependency(CmsContentWidgetAntelopeConnectorDependencyProvider::CLIENT_ANTELOPE);
    }
}