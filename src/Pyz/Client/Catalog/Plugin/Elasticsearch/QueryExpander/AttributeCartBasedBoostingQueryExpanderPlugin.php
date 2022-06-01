<?php

namespace Pyz\Client\Catalog\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\FunctionScore;
use Elastica\Query\MultiMatch;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use InvalidArgumentException;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Shared\Kernel\Store;

class AttributeCartBasedBoostingQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = [])
    {
        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        // Don't need to change query when cart is empty.
        if (!$quoteTransfer->getItems()->count()) {
            return $searchQuery;
        }

        // Make sure that the query we are extending is compatible with our expectations.
        $boolQuery = $this->getBoolQuery($searchQuery->getSearchQuery());

        // Boost query based on cart.
        $this->boostByCartItemColors($boolQuery, $quoteTransfer);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @throws \InvalidArgumentException
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function getBoolQuery(Query $query)
    {
        $boolQuery = $query->getQuery();
        if (!$boolQuery instanceof BoolQuery) {
            throw new InvalidArgumentException(sprintf(
                'Cart boost query expander available only with %s, got: %s',
                BoolQuery::class,
                get_class($boolQuery)
            ));
        }

        return $boolQuery;
    }

    /**
     * @param \Elastica\Query\BoolQuery $boolQuery
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function boostByCartItemColors(BoolQuery $boolQuery, QuoteTransfer $quoteTransfer)
    {
        $functionScoreQuery = new FunctionScore();
        // Define how the computed scores are combined for the used functions.
        $functionScoreQuery->setScoreMode(FunctionScore::SCORE_MODE_MULTIPLY);
        // Define how the newly computed score is combined with the score of the query.
        $functionScoreQuery->setBoostMode(FunctionScore::BOOST_MODE_MULTIPLY);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $color = $this->getProductColor($itemTransfer);

            if ($color) {
                // Create filter for all products that contains the same color.
                $filter = $this->createFulltextSearchQuery($color);

                // Boost the results with a custom number.
                $functionScoreQuery->addFunction('weight', 20, $filter);
            }
        }

        // Extend the original search query with function_score that will change the score of the results.
        $boolQuery->addMust($functionScoreQuery);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return null
     */
    protected function getProductColor(ItemTransfer $itemTransfer)
    {
        // We get the concrete product from the key-value storage (Redis).
        $productData = $this->getFactory()
            ->getProductStorageClient()
            ->getProductAbstractStorageData($itemTransfer->getIdProductAbstract(), Store::getInstance()->getCurrentLocale());

        return isset($productData['attributes']['color']) ? $productData['attributes']['color'] : null;
    }

    /**
     * @param string $searchString
     *
     * @return \Elastica\Query\MultiMatch
     */
    protected function createFulltextSearchQuery($searchString)
    {
        // We search for color in the "full-text" and "full-text-boosted" fields.
        $matchQuery = (new MultiMatch())
            ->setFields([
                PageIndexMap::FULL_TEXT,
                PageIndexMap::FULL_TEXT_BOOSTED . '^3', // Boost results with custom number.
            ])
            ->setQuery($searchString)
            ->setType(MultiMatch::TYPE_CROSS_FIELDS);

        return $matchQuery;
    }
}