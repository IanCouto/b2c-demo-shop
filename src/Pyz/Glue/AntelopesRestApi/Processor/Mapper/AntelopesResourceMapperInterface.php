<?php

namespace Pyz\Glue\AntelopesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestAntelopesAttributesTransfer;

interface AntelopesResourceMapperInterface
{
    public function mapAntelopeDataToAntelopeRestAttributes(array $antelopeData): RestAntelopesAttributesTransfer;
}