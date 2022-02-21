<?php

namespace Pyz\Client\PersonalizedProduct\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\FunctionScore;
use Elastica\Query\Match;
use Elastica\Query\MatchAll;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;
use Spryker\Shared\ProductSearch\ProductSearchConfig;

class PersonalizedProductQueryPlugin implements QueryInterface, SearchContextAwareQueryInterface
{
    protected const SOURCE_IDENTIFIER = 'page';

    /**
     * @var \Generated\Shared\Transfer\SearchContextTransfer
     */
    private $searchContextTransfer;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @param int $limit
     */
    public function __construct($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return \Elastica\Query
     */
    public function getSearchQuery()
    {
        $boolQuery = (new BoolQuery())
            ->addMust((new FunctionScore())
                ->setQuery(new MatchAll())
                ->addFunction('random_score', ['seed' => session_id()])
                ->setScoreMode('sum'))
            ->addMust((new Match())
                ->setField(PageIndexMap::TYPE, ProductSearchConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT));

        $query = (new Query())
            ->setSource([PageIndexMap::SEARCH_RESULT_DATA])
            ->setQuery($boolQuery)
            ->setSize($this->limit);

        return $query;
    }

    /**
     * {@inheritDoc}
     * - Defines a context for sale search.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function getSearchContext(): SearchContextTransfer
    {
        if (!$this->hasSearchContext()) {
            $this->setupDefaultSearchContext();
        }

        return $this->searchContextTransfer;
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return void
     */
    public function setSearchContext(SearchContextTransfer $searchContextTransfer): void
    {
        $this->searchContextTransfer = $searchContextTransfer;
    }

    /**
     * @return void
     */
    protected function setupDefaultSearchContext(): void
    {
        $searchContextTransfer = new SearchContextTransfer();
        $searchContextTransfer->setSourceIdentifier(static::SOURCE_IDENTIFIER);

        $this->searchContextTransfer = $searchContextTransfer;
    }

   /**
     * @return bool
     */
    protected function hasSearchContext(): bool
    {
        return (bool)$this->searchContextTransfer;
    }
}