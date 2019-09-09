<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ScandiPWA\CmsGraphQl\Model\Page\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\View\Model\PageWidth\Config\BuilderInterface;

/**
 * Class PageWidth
 */
class PageWidth implements OptionSourceInterface
{
    /**
     * @var \Magento\Framework\View\Model\PageWidth\Config\BuilderInterface
     */
    protected $pageWidthBuilder;

    /**
     * @var array
     * @deprecated 103.0.1 since the cache is now handled by \Magento\Theme\Model\PageWidth\Config\Builder::$configFiles
     */
    protected $options;

    /**
     * Constructor
     *
     * @param BuilderInterface $pageWidthBuilder
     */
    public function __construct(BuilderInterface $pageWidthBuilder)
    {
        $this->pageWidthBuilder = $pageWidthBuilder;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        // $configOptions = $this->pageWidthBuilder->getPageWidthsConfig()->getOptions();
        $configOptions = [
            'pizza' => '12',
            'anotehr' => '1444'
        ];
        $options = [];
        foreach ($configOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        $this->options = $options;

        return $options;
    }
}
