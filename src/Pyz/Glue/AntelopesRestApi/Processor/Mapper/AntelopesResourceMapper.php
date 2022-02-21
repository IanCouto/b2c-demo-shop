<?php

namespace Pyz\Glue\AntelopesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestAntelopesAttributesTransfer;

class AntelopesResourceMapper implements AntelopesResourceMapperInterface
{
    public function mapAntelopeDataToAntelopeRestAttributes(array $antelopeData): RestAntelopesAttributesTransfer
    {
        $restAntelopeAttributesTransfer = (new RestAntelopesAttributesTransfer())->fromArray($antelopeData, true);

        return $restAntelopeAttributesTransfer;
    }
}