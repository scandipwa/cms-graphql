<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ScandiPWA\Cms\Api\Data;

/**
 * CMS page interface.
 * @api
 * @since 100.0.2
 */
interface CustomPageInterface extends Magento\Cms\Api\Data\PageInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const PAGE_WIDTH = 'page_width';
    /**#@-*/

    /**
     * Get page width
     *
     * @return string|null
     */
    public function getPageWidth();

    /**
     * Set page width
     *
     * @param string $pageWidth
     * @return \Magento\Cms\Api\Data\PageInterface
     */
    public function setPageWidth($pageWidth);

}
