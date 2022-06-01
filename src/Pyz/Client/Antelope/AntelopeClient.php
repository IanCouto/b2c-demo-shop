<?php

namespace Pyz\Client\Antelope;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Pyz\Client\Antelope\AntelopeFactory getFactory()
 */
class AntelopeClient extends AbstractClient
{
    /**
     * @param string $name
     *
     * @return array
     */
    public function getAntelopeByName(string $name): array
    {
        $searchQuery = $this->getFactory()
            ->createAntelopeQueryPlugin($name);

        $resultFormatters = $this->getFactory()
            ->getSearchQueryFormatters();

        $searchResults = $this->getFactory()
            ->getSearchClient()
            ->search(
                $searchQuery,
                $resultFormatters
            );

        return $searchResults['antelope'] ?? [];
    }
}