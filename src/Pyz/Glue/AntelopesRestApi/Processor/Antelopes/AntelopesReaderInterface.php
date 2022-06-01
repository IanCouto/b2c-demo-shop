<?php

namespace Pyz\Glue\AntelopesRestApi\Processor\Antelopes;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface AntelopesReaderInterface
{
    public function getAntelopeSearchData(RestRequestInterface $restRequest): RestResponseInterface;
    public function findAntelopeByName(string $name, RestRequestInterface $restRequest): ?RestResourceInterface;
}