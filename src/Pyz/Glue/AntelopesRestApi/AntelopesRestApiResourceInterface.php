<?php

namespace Pyz\Glue\AntelopesRestApi;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface AntelopesRestApiResourceInterface
{
    public function findAntelopeByName(string $name, RestRequestInterface $restRequest): ?RestResourceInterface;
}