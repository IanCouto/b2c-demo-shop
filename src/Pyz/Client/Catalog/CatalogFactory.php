<?php

namespace Pyz\Client\Catalog;

use Spryker\Client\Catalog\CatalogFactory as SprykerCatalogFactory;

class CatalogFactory extends SprykerCatalogFactory
{
    /**
     * @throws \Spryker\Client\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \Spryker\Client\Cart\CartClientInterface
     */
    public function getCartClient()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::CLIENT_CART);
    }
    
    /**
     * @throws \Spryker\Client\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    public function getProductStorageClient()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }
}