<?php

namespace Pyz\Glue\AntelopesRestApi;

use Pyz\Client\Antelope\AntelopeClientInterface;
use Pyz\Glue\AntelopesRestApi\Processor\Antelopes\AntelopesReader;
use Pyz\Glue\AntelopesRestApi\Processor\Antelopes\AntelopesReaderInterface;
use Pyz\Glue\AntelopesRestApi\Processor\Mapper\AntelopesResourceMapper;
use Spryker\Glue\Kernel\AbstractFactory;

class AntelopesRestApiFactory extends AbstractFactory
{
    public function createAntelopeResourceMapper(): AntelopesResourceMapper
    {
        return new AntelopesResourceMapper();
    }

    public function createAntelopesReader(): AntelopesReaderInterface
    {
        return new AntelopesReader(
            $this->getAntelopeClient(),
            $this->getResourceBuilder(),
            $this->createAntelopeResourceMapper()
        );
    }

    public function getAntelopeClient(): AntelopeClientInterface
    {
        return $this->getProvidedDependency(AntelopesRestApiDependencyProvider::CLIENT_ANTELOPE);
    }
}