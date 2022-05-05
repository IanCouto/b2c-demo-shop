<?php

namespace Pyz\Zed\AntelopeSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\AntelopeSearch\Business\AntelopeSearchBusinessFactory getFactory()
 */
class AntelopeSearchFacade extends AbstractFacade implements AntelopeSearchFacadeInterface
{
    /**
     * @param int $idAntelope
     *
     * @return void
     */
    public function publish(int $idAntelope): void
    {
        $this->getFactory()
            ->createAntelopeSearchWriter()
            ->publish($idAntelope);
    }
}