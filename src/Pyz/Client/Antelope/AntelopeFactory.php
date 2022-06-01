<?php

namespace Pyz\Client\Antelope;

use Pyz\Client\Antelope\Plugin\Elasticsearch\Query\AntelopeQueryPlugin;
use Spryker\Client\Kernel\AbstractFactory;

class AntelopeFactory extends AbstractFactory
{
    /**
     * @param string $name
     *
     * @return \Pyz\Client\Antelope\Plugin\Elasticsearch\Query\AntelopeQueryPlugin
     */
    public function createAntelopeQueryPlugin(string $name)
    {
        return new AntelopeQueryPlugin($name);
    }

    /**
     * @return array
     */
    public function getSearchQueryFormatters()
    {
        return $this->getProvidedDependency(AntelopeDependencyProvider::ANTELOPE_RESULT_FORMATTER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    public function getSearchClient()
    {
        return $this->getProvidedDependency(AntelopeDependencyProvider::CLIENT_SEARCH);
    }
}