<?php

namespace Pyz\Yves\PersonalizedProduct\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class PersonalizedProductRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const PERSONALIZED_PRODUCT_INDEX = 'personalized-product-index';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addPersonalizedProductRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPersonalizedProductRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/personalized-product/{limit}',
            'PersonalizedProduct',
            'Index',
            'indexAction'
        );

        $route->addDefaults(['limit' => 10]);
        $route->addRequirements(['limit' => '\d+']);

        $routeCollection->add(static::PERSONALIZED_PRODUCT_INDEX, $route);

        return $routeCollection;
    }
}