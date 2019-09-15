<?php
/**
 * ScandiPWA_CmsGraphQl
 *
 * @category    Scandiweb
 * @package     ScandiPWA_CmsGraphQl
 * @copyright   Copyright (c) 2018 Scandiweb, Ltd (https://scandiweb.com)
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
     */
    protected $options;

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        $configOptions = [
            'default' => 'default',
            'full' => 'full'
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
