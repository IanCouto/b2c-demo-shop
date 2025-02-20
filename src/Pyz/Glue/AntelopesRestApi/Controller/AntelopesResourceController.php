<?php

namespace Pyz\Glue\AntelopesRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Pyz\Glue\AntelopesRestApi\AntelopesRestApiFactory getFactory()
 */
class AntelopesResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves Antelopes by name."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responses": {
     *              "400": "Antelope uuid is not specified.",
     *              "404": "Antelope not found."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this->getFactory()
            ->createAntelopesReader()
            ->getAntelopeSearchData($restRequest);
    }
}