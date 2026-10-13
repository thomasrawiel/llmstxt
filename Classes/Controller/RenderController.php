<?php
declare(strict_types=1);

namespace LINGNER\Llmstxt\Controller;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use LINGNER\Llmstxt\Service\PageService;
use LINGNER\Llmstxt\Utility\ConfigurationUtility;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Core\Attribute\AsAllowedCallable;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Error\Http\PageNotFoundException;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\ErrorController;
use TYPO3\CMS\Frontend\Page\PageAccessFailureReasons;

#[Autoconfigure(public: true)]
final readonly class RenderController
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private StreamFactoryInterface   $streamFactory,
        private ConnectionPool           $connectionPool,
        private ConfigurationUtility     $configurationUtility,
    )
    {

    }

    /**
     * @throws PageNotFoundException
     * @throws Exception
     */
    #[AsAllowedCallable]
    public function llmstxtResponse(ServerRequestInterface $request): ResponseInterface
    {
        $txt = $this->getLLmsTxtForPage($request);

        if ($txt === false) {
            return $this->errorResponse($request);
        }

        return $this->txtResponse($txt);
    }

    /**
     * @throws Exception
     */
    private function getLLmsTxtForPage(ServerRequestInterface $request): string|false
    {
        $router = $request->getAttribute('routing');
        $site = $request->getAttribute('site');
        if ($router === null) {
            return false;
        }

        $pageUid = $router->getPageId();

        if ($site === null || ($pageUid !== $site->getRootPageId() && !$this->configurationUtility->addToAllPages())) {
            return false;
        }

        $language = $request->getAttribute('language');
        $ln = 0;
        if ($language !== null) {
            $ln = $language->isEnabled() ? $language->getLanguageId() : 0;
        }

        if ($ln > 0 && !$this->configurationUtility->addTranslations()) {
            return false;
        }

        $qb = $this->connectionPool->getQueryBuilderForTable('pages');
        $result = $qb->select('llmstxt_enable', 'llmstxt')
            ->from('pages')
            ->where(
                $qb->expr()->eq($ln > 0 ? 'l10n_parent' : 'uid', $qb->createNamedParameter($pageUid, ParameterType::INTEGER)),
                $qb->expr()->eq('sys_language_uid', $qb->createNamedParameter($ln, ParameterType::INTEGER)),
            )->executeQuery()
            ->fetchAssociative();

        return (bool)$result['llmstxt_enable'] ? trim($result['llmstxt']) : false;
    }

    private function txtResponse(?string $txt = null): ResponseInterface
    {
        return $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'text/plain; charset=utf-8')
            ->withBody($this->streamFactory->createStream($txt));
    }

    /**
     * @throws PageNotFoundException
     */
    private function errorResponse(ServerRequestInterface $request): ResponseInterface
    {
        return GeneralUtility::makeInstance(ErrorController::class)->pageNotFoundAction(
            $request,
            'The requested page does not exist',
            ['code' => PageAccessFailureReasons::NO_PAGES_FOUND]
        );
    }
}
