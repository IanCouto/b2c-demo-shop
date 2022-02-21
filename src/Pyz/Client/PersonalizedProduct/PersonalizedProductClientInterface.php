<?php
namespace Pyz\Client\PersonalizedProduct;

interface PersonalizedProductClientInterface
{
    /**
     * @param int $limit
     *
     * @return array
     */
    public function getPersonalizedProducts(int $limit);
}