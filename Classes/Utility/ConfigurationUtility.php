<?php
declare(strict_types=1);

namespace LINGNER\Llmstxt\Utility;

use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ConfigurationUtility
{
    private array $settings;

    /**
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     */
    public function __construct()
    {
        $this->settings = (GeneralUtility::makeInstance(ExtensionConfiguration::class))
            ->get('llmstxt');
    }

    public function addToAllPages(): bool
    {
        return (bool)$this->settings['allPages'];
    }

    public function addTranslations(): bool
    {
        return (bool)$this->settings['translations'];
    }
}
