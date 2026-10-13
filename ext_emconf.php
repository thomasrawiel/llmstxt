<?php
declare(strict_types=1);

$EM_CONF[$_EXTKEY] = [
    'title' => 'llmstxt',
    'description' => 'Schema.org',
    'category' => 'misc',
    'author' => 'Thomas Rawiel',
    'author_email' => 't.rawiel@lingner.com',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.20-14.4.99',
            'filemetadata' => '13.4.20-14.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
