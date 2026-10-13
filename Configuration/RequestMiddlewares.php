<?php
declare(strict_types=1);

return [
    'frontend' => [
        'lingner/llmstxt' => [
            'target' => \LINGNER\Llmstxt\Middleware\LlmtxtMiddleware::class,
            'before' => [
                'typo3/cms-frontend/tsfe', //TYPO3v13
                'typo3/cms-frontend/prepare-tsfe-rendering' //TYPO3v14
            ],
            'after' => [
                'typo3/cms-frontend/page-argument-validator',
            ],
        ],
    ],
];
