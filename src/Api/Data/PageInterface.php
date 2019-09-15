<?php
/**
 * ScandiPWA_CmsGraphQl
 *
 * @category    Scandiweb
 * @package     ScandiPWA_CmsGraphQl
 * @copyright   Copyright (c) 2018 Scandiweb, Ltd (https://scandiweb.com)
 */

namespace ScandiPWA\CmsGraphQl\Api\Data;

/**
 * CMS page interface.
 * @api
 * @since 100.0.2
 */
interface PageInterface extends \Magento\Cms\Api\Data\PageInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const URL_KEY                  = 'url_key';
    const PAGE_WIDTH               = 'page_width';
    /**#@-*/
}
