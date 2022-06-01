<?php

namespace Pyz\Glue\AntelopesRestApi\Processor\Antelopes;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Pyz\Client\Antelope\AntelopeClientInterface;
use Pyz\Glue\AntelopesRestApi\AntelopesRestApiConfig;
use Pyz\Glue\AntelopesRestApi\Processor\Mapper\AntelopesResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class AntelopesReader implements AntelopesReaderInterface
{
    /** @var AntelopeClientInterface */
    protected $antelopeClient;

    /** @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface */
    protected $restResourceBuilder;

    /** @var \Pyz\Glue\AntelopesRestApi\Processor\Mapper\AntelopesResourceMapperInterface */
    protected $antelopesResourceMapper;

    public function __construct(
        AntelopeClientInterface $antelopeClient,
        RestResourceBuilderInterface $restResourceBuilder,
        AntelopesResourceMapperInterface $antelopesResourceMapper
    ) {
        $this->antelopeClient = $antelopeClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->antelopesResourceMapper = $antelopesResourceMapper;
    }

    public function getAntelopeSearchData(RestRequestInterface $restRequest): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse();

        $resourceIdentifier = $restRequest->getResource()->getId();

        if (!$resourceIdentifier) {
            return $this->addAntelopeNameSpecifiedError($response);
        }

        $restResource = $this->findAntelopeByName($resourceIdentifier, $restRequest);

        if (!$restResource) {
            return $this->addAntelopeNotFoundError($response);
        }

        return $response->addResource($restResource);
    }

    public function findAntelopeByName(string $name, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $antelopeData = $this->antelopeClient->getAntelopeByName($name);

        if (empty($antelopeData)) {
            return null;
        }

        return $this->createRestResourceFromAntelopeSearchData($antelopeData, $restRequest);
    }

    protected function createRestResourceFromAntelopeSearchData(array $antelopeData, RestRequestInterface $restRequest): RestResourceInterface
    {
        $restAntelopeAttributesTransfer = $this->antelopesResourceMapper
            ->mapAntelopeDataToAntelopeRestAttributes($antelopeData);

        return $this->restResourceBuilder->createRestResource(
            AntelopesRestApiConfig::RESOURCE_ANTELOPES,
            $restAntelopeAttributesTransfer->getName(),
            $restAntelopeAttributesTransfer
        );
    }

    protected function addAntelopeNameSpecifiedError(RestResponseInterface $response): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(AntelopesRestApiConfig::RESPONSE_CODE_ANTELOPE_NAME_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(AntelopesRestApiConfig::RESPONSE_DETAIL_ANTELOPE_NAME_IS_NOT_SPECIFIED);

        return $response->addError($restErrorTransfer);
    }

    protected function addAntelopeNotFoundError(RestResponseInterface $response): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(AntelopesRestApiConfig::RESPONSE_CODE_CANT_FIND_ANTELOPE)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(AntelopesRestApiConfig::RESPONSE_DETAIL_CANT_FIND_ANTELOPE);

        return $response->addError($restErrorTransfer);
    }
}