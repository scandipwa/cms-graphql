<?php
/**
 * ScandiPWA_CmsGraphQl
 *
 * @category    Scandiweb
 * @package     ScandiPWA_CmsGraphQl
 * @author      Artjoms Travkovs <artjoms.travkovs@scandiweb.com>
 * @copyright   Copyright (c) 2018 Scandiweb, Ltd (https://scandiweb.com)
 */

namespace ScandiPWA\CmsGraphQl\Model\Resolver\Attribute;

use ScandiPWA\CmsGraphQl\Api\AttributeHandlerInterface;

class ConditionsEncoded implements AttributeHandlerInterface
{
    public function resolve(string $value): string
    {
        return base64_encode($value);
    }
}
