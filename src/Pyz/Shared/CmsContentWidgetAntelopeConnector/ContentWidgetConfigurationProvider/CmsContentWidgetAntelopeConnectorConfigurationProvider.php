<?php

namespace Pyz\Shared\CmsContentWidgetAntelopeConnector\ContentWidgetConfigurationProvider;

use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;

class CmsContentWidgetAntelopeConnectorConfigurationProvider implements CmsContentWidgetAntelopeConnectorConfigurationProviderInterface
{
    public const FUNCTION_NAME = 'antelope';

/**
     * @return string
     */
    public function getFunctionName(): string
    {
        return static::FUNCTION_NAME;
    }

/**
     * @return array
     */
    public function getAvailableTemplates(): array
    {
        return [
            CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER => '@CmsContentWidgetAntelopeConnector/views/cms-antelope/cms-antelope.twig',
        ];
    }

/**
     * @return string
     */
    public function getUsageInformation(): string
    {
        return "{{ antelope(['name']) }}. To use a different template {{ antelope(['name'], 'default') }}.";
    }
}