<?php

namespace Pyz\Shared\CmsContentWidgetAntelopeConnector\ContentWidgetConfigurationProvider;

use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;

interface CmsContentWidgetAntelopeConnectorConfigurationProviderInterface extends CmsContentWidgetConfigurationProviderInterface
{
/**
     * @return string
     */
    public function getFunctionName(): string;

/**
     * @return array
     */
    public function getAvailableTemplates(): array;

/**
     * @return string
     */
    public function getUsageInformation(): string;
}