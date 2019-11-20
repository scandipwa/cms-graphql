<?php
/**
 * ScandiPWA_CmsGraphQl
 *
 * @category    Scandiweb
 * @package     ScandiPWA_CmsGraphQl
 * @copyright   Copyright (c) 2018 Scandiweb, Ltd (https://scandiweb.com)
 */

declare(strict_types=1);

namespace ScandiPWA\CmsGraphQl\Model\Resolver\DataProvider;

use Magento\Framework\Exception\LocalizedException;
use ScandiPWA\CmsGraphQl\Api\Data\PageInterface;
use Magento\Cms\Api\Data\PageInterface as OriginalPageInterface;
use Magento\Cms\Api\GetPageByIdentifierInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Widget\Model\Template\FilterEmulate;

/**
 * Cms page data provider
 */
class Page extends \Magento\CmsGraphQl\Model\Resolver\DataProvider\Page
{
    /**
     * @var GetPageByIdentifierInterface
     */
    private $pageByIdentifier;

    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var FilterEmulate
     */
    private $widgetFilter;

    /**
     * @param PageRepositoryInterface $pageRepository
     * @param FilterEmulate $widgetFilter
     * @param GetPageByIdentifierInterface $getPageByIdentifier
     */
    public function __construct(
        PageRepositoryInterface $pageRepository,
        FilterEmulate $widgetFilter,
        GetPageByIdentifierInterface $getPageByIdentifier
    ) {

        $this->pageRepository = $pageRepository;
        $this->widgetFilter = $widgetFilter;
        $this->pageByIdentifier = $getPageByIdentifier;
    }

    /**
     * @param int $pageId
     * @return array
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function getDataByPageId(int $pageId): array
    {
        $page = $this->pageRepository->getById($pageId);

        return $this->convertPageData($page);
    }

    /**
     * @param string $pageIdentifier
     * @param int $storeId
     * @return array
     * @throws NoSuchEntityException
     */
    public function getDataByPageIdentifier(string $pageIdentifier, int $storeId): array
    {
        $page = $this->pageByIdentifier->execute($pageIdentifier, $storeId);

        return $this->convertPageData($page);
    }

    /**
     * Convert page data
     *
     * @param OriginalPageInterface $page
     * @return array
     * @throws NoSuchEntityException
     */
    private function convertPageData(OriginalPageInterface $page)
    {
        if (false === $page->isActive()) {
            throw new NoSuchEntityException();
        }

        $renderedContent = $this->widgetFilter->filter($page->getContent());

        $pageData = [
            PageInterface::URL_KEY => $page->getIdentifier(),
            PageInterface::TITLE => $page->getTitle(),
            PageInterface::CONTENT => $renderedContent,
            PageInterface::CONTENT_HEADING => $page->getContentHeading(),
            PageInterface::PAGE_LAYOUT => $page->getPageLayout(),
            PageInterface::PAGE_WIDTH => $page->getPageWidth() ?: 'default',
            PageInterface::META_TITLE => $page->getMetaTitle(),
            PageInterface::META_DESCRIPTION => $page->getMetaDescription(),
            PageInterface::META_KEYWORDS => $page->getMetaKeywords(),
            PageInterface::PAGE_ID => $page->getId(),
            PageInterface::IDENTIFIER => $page->getIdentifier(),
        ];

        return $pageData;
    }
}
