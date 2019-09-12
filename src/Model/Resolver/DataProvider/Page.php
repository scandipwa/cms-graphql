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

use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Widget\Model\Template\FilterEmulate;

/**
 * Cms page data provider
 */
class Page extends \Magento\CmsGraphQl\Model\Resolver\DataProvider\Page
{
    /**
     * @var FilterEmulate
     */
    private $widgetFilter;

    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @param PageRepositoryInterface $pageRepository
     * @param FilterEmulate $widgetFilter
     */
    public function __construct(
        PageRepositoryInterface $pageRepository,
        FilterEmulate $widgetFilter
    ) {
        $this->pageRepository = $pageRepository;
        $this->widgetFilter = $widgetFilter;
    }

    /**
     * @param int $pageId
     * @return array
     * @throws NoSuchEntityException
     */
    public function getData(int $pageId): array
    {
        $page = $this->pageRepository->getById($pageId);

        if (false === $page->isActive()) {
            throw new NoSuchEntityException();
        }

        $renderedContent = $this->widgetFilter->filter($page->getContent());

        $pageData = [
            'url_key' => $page->getIdentifier(),
            PageInterface::TITLE => $page->getTitle(),
            PageInterface::CONTENT => $renderedContent,
            PageInterface::CONTENT_HEADING => $page->getContentHeading(),
            PageInterface::PAGE_LAYOUT => $page->getPageLayout(),
            'page_width' => $page->getPageWidth() ?: 'default',
            PageInterface::META_TITLE => $page->getMetaTitle(),
            PageInterface::META_DESCRIPTION => $page->getMetaDescription(),
            PageInterface::META_KEYWORDS => $page->getMetaKeywords(),
        ];

        return $pageData;
    }
}
