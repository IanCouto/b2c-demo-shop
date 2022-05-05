<?php

namespace Pyz\Zed\AntelopeSearch\Business;

use Pyz\Zed\AntelopeSearch\Business\Writer\AntelopeSearchWriter;

class AntelopeSearchBusinessFactory
{
    /**
     * @return \Pyz\Zed\AntelopeSearch\Business\Writer\AntelopeSearchWriter
     */
    public function createAntelopeSearchWriter()
    {
        return new AntelopeSearchWriter();
    }
}