<?php
declare(strict_types=1);

return [
    'frontend' => [
        'lingner/llmstxt' => [
            'target' => \LINGNER\Llmstxt\Middleware\LlmtxtMiddleware::class,
            'before' => [
                'typo3/cms-frontend/tsfe',
            ],
            'after' => [
                'typo3/cms-frontend/page-argument-validator',
            ],
        ],
    ],
];
