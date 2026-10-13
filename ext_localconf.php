<?php
declare(strict_types=1);

defined('TYPO3') || die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
    'llmstxt',
    'setup',
    "@import 'EXT:llmstxt/Configuration/TypoScript/setup.typoscript'"
);
