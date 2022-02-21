<?php

namespace Pyz\Glue\AntelopesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class AntelopesRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_ANTELOPES = 'antelopes';
    public const RESPONSE_CODE_CANT_FIND_ANTELOPE = '301';
    public const RESPONSE_DETAIL_CANT_FIND_ANTELOPE = 'Antelope was not found.';
    public const RESPONSE_CODE_ANTELOPE_NAME_IS_NOT_SPECIFIED = '311';
    public const RESPONSE_DETAIL_ANTELOPE_NAME_IS_NOT_SPECIFIED = 'Antelope name is not specified.';
}