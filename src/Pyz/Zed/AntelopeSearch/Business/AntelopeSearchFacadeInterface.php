<?php

namespace Pyz\Zed\AntelopeSearch\Business;

interface AntelopeSearchFacadeInterface
{
    /**
     * @param int $idAntelope
     *
     * @return void
     */
    public function publish(int $idAntelope): void;
}