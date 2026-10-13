<?php
declare(strict_types=1);

namespace LINGNER\Llmstxt\Middleware;

use Doctrine\DBAL\Exception;
use LINGNER\Llmstxt\Controller\RenderController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Error\Http\PageNotFoundException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class LlmtxtMiddleware implements MiddlewareInterface
{
    private const int pageType = 1788340978;

    /**
     * @throws PageNotFoundException
     * @throws Exception
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        if($this->verify($request)) {
            return (GeneralUtility::makeInstance(RenderController::class))
                ->llmstxtResponse($request);
        }

        return $handler->handle($request);
    }

    private function verify(ServerRequestInterface $request): bool {
        $queryParams = $request->getQueryParams();
        $page = $request->getAttribute('routing');

        return (int)($queryParams['type'] ?? 0) === self::pageType
            || (int)$page->getPageType() === self::pageType;

    }
}
