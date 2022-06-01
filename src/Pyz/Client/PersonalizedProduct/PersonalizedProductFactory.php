<?php

namespace Pyz\Client\PersonalizedProduct;

use Pyz\Client\PersonalizedProduct\Plugin\Elasticsearch\Query\PersonalizedProductQueryPlugin;
use Spryker\Client\Kernel\AbstractFactory;

class PersonalizedProductFactory extends AbstractFactory
{
    /**
     * @param int $limit
     *
     * @return \Pyz\Client\PersonalizedProduct\Plugin\Elasticsearch\Query\PersonalizedProductQueryPlugin
     */
    public function createPersonalizedProductsQueryPlugin(int $limit)
    {
        return new PersonalizedProductQueryPlugin($limit);
    }

    /**
     * @return array
     */
    public function getSearchQueryFormatters()
    {
        return $this->getProvidedDependency(PersonalizedProductDependencyProvider::CATALOG_SEARCH_RESULT_FORMATTER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    public function getSearchClient()
    {
        return $this->getProvidedDependency(PersonalizedProductDependencyProvider::CLIENT_SEARCH);
    }
}