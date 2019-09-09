<?php

namespace ScandiPWA\CmsGraphQL\Model;

use Magento\Cms\Model\Page\Source\PageLayout as BasePageLayout;

class PageLayout extends BasePageLayout{

    public function toOptionArray()
    {
        $options = parent::toOptionArray();
        array_merge($options, [
            'me'=>'pizza'
        ])
        return $options;
    }
}