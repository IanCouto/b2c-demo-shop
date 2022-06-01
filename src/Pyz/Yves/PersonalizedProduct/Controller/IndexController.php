<?php

namespace Pyz\Yves\PersonalizedProduct\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @param int $limit
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(int $limit)
    {
        $searchResults = $this->getClient()->getPersonalizedProducts($limit);
 
        return $this->view(
            $searchResults,
            [],
            '@PersonalizedProduct/views/index/index.twig'
        );
    }
}