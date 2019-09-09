<?php
/**
 * ScandiPWA_CmsGraphQl
 *
 * @category    Scandiweb
 * @package     ScandiPWA_CmsGraphQl
 * @copyright   Copyright (c) 2018 Scandiweb, Ltd (https://scandiweb.com)
 */

namespace ScandiPWA\CmsGraphQl\Model\Resolver\DataProvider;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Widget\Model\Template\FilterEmulate;

/**
 * Class Block
 * @package ScandiPWA\CmsGraphQl\Model\Resolver\DataProvider
 */
class Block extends \Magento\CmsGraphQl\Model\Resolver\DataProvider\Block
{
    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    /**
     * @var FilterEmulate
     */
    private $widgetFilter;

    /**
     * @param BlockRepositoryInterface $blockRepository
     * @param FilterEmulate $widgetFilter
     */
    public function __construct(
        BlockRepositoryInterface $blockRepository,
        FilterEmulate $widgetFilter
    ) {
        $this->blockRepository = $blockRepository;
        $this->widgetFilter = $widgetFilter;
    }

    /**
     * Get block data
     *
     * @param string $blockIdentifier
     * @return array
     * @throws NoSuchEntityException
     */
    public function getData(string $blockIdentifier): array
    {
        $block = $this->blockRepository->getById($blockIdentifier);

        if (!$block->isActive()) {
            return [
                BlockInterface::IDENTIFIER => $block->getIdentifier(),
                'disabled' => true
            ];
        }

        $renderedContent = $this->widgetFilter->filter($block->getContent());

        return [
            BlockInterface::IDENTIFIER => $block->getIdentifier(),
            BlockInterface::TITLE => $block->getTitle(),
            BlockInterface::CONTENT => $renderedContent,
            'disabled' => false
        ];
    }
}
