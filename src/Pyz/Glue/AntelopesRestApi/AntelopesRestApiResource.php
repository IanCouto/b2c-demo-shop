<?php

namespace Pyz\Glue\AntelopesRestApi;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\AbstractRestResource;

/**
 * @method \Pyz\Glue\AntelopesRestApi\AntelopesRestApiFactory getFactory()
 */
class AntelopesRestApiResource extends AbstractRestResource implements AntelopesRestApiResourceInterface
{
    public function findAntelopeByName(string $name, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        return $this->getFactory()
            ->createAntelopesReader()
            ->findAntelopeByName($name, $restRequest);
    }
}