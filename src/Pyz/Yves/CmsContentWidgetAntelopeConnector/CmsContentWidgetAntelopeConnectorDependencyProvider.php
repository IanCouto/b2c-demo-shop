<?php

namespace Pyz\Yves\CmsContentWidgetAntelopeConnector;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class CmsContentWidgetAntelopeConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_ANTELOPE = 'CLIENT_ANTELOPE';

/**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
$container = parent::provideDependencies($container);
$container = $this->addCmsBlockStorageClient($container);

        return $container;
    }

/**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsBlockStorageClient(Container $container): Container
    {
$container[static::CLIENT_ANTELOPE] = function (Container $container) {
            return $container->getLocator()->antelope()->client();
        };

        return $container;
    }
}