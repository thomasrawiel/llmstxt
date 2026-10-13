<?php
declare(strict_types=1);


use LINGNER\Llmstxt\Events\AllowedDoktypesEvent;
use LINGNER\Llmstxt\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') || die('Access denied.');

call_user_func(function (): void {
    $conf = GeneralUtility::makeInstance(ConfigurationUtility::class);

    $enableField = [
        'exclude' => true,
        'label' => 'Enable',
        'l10n_mode' => $conf->addTranslations() ? 'prefixLangTitle' : 'exclude',
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'default' => 0,
        ],
    ];
    $llmsTxtField = [
        'exclude' => true,
        'label' => 'LLMSTXT content',
        'l10n_mode' => $conf->addTranslations() ? 'prefixLangTitle' : 'exclude',
        'config' => [
            'type' => 'text',
            'renderType' => 'codeEditor',
        ],
    ];
    if (!$conf->addToAllPages()) {
        $displayCond = 'FIELD:is_siteroot:REQ:true';
        $enableField['displayCond'] = $displayCond;
        $llmsTxtField['displayCond'] = $displayCond;
    }


    ExtensionManagementUtility::addTCAcolumns('pages', [
        'llmstxt_enable' => $enableField,
        'llmstxt' => $llmsTxtField,
    ]);

    $GLOBALS['TCA']['pages']['palettes']['llmstxt'] = [
        'label' => 'LLMSTXT',
        'showitem' => 'llmstxt_enable,--linebreak--,llmstxt',
    ];

    $eventDispatcher = GeneralUtility::makeInstance(EventDispatcher::class);
    $allowedDoktypesEvent = $eventDispatcher->dispatch(new AllowedDoktypesEvent([1]));


    ExtensionManagementUtility::addToAllTCAtypes(
        'pages', '--palette--;;llmstxt', (string)$allowedDoktypesEvent, 'after:TSconfig');
});
