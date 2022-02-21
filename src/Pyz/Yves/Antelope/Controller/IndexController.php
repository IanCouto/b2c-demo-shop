<?php

namespace Pyz\Yves\Antelope\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;

/**
 * @method \Pyz\Client\Antelope\AntelopeClientInterface getClient()
 */
class IndexController extends AbstractController
{
    /**
     * @param string $name
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(string $name)
    {
        $antelope = $this->getClient()->getAntelopeByName($name);

        return $this->view(
            [
                'antelope' => $antelope,
            ],
            [],
            '@Antelope/views/index/index.twig'
        );
    }
}