<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ScandiPWA\CmsGraphQl\Model\Page\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\View\Model\PageLayout\Config\BuilderInterface;

/**
 * Class PageWidth
 */
class PageWidth implements OptionSourceInterface
{
    /**
     * @var array
     * @deprecated 103.0.1 since the cache is now handled by \Magento\Theme\Model\PageWidth\Config\Builder::$configFiles
     */
    protected $options;

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        // $configOptions = $this->pageWidthBuilder->getPageWidthsConfig()->getOptions();
        $configOptions = [
            'default' => 'default',
            'full' => 'full width'
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
