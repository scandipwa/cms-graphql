<?php
/**
 * ScandiPWA_CmsGraphQl
 *
 * @category    Scandiweb
 * @package     ScandiPWA_CmsGraphQl
 * @author      Artjoms Travkovs <artjoms.travkovs@scandiweb.com>
 * @copyright   Copyright (c) 2018 Scandiweb, Ltd (https://scandiweb.com)
 */

namespace ScandiPWA\CmsGraphQl\Model\Template;

/**
 * Class FilterEmulate
 * @package ScandiPWA\CmsGraphQl\Model\Template
 */
class FilterEmulate extends \Magento\Widget\Model\Template\FilterEmulate
{

    /**
     * Array of keys that will not be escaped
     * in custom widget html output
     *
     * @var string[]
     */
    public static $widgetParamsWhitelist = ['type'];

    /**
     * General method for generate widget
     *
     * @param string[] $construction
     * @return string
     */
    public function generateWidget($construction)
    {
        $params = $this->getParameters($construction[2]);

        // Determine what name block should have in layout
        $name = null;
        if (isset($params['name'])) {
            $name = $params['name'];
        }

        if (isset($this->_storeId) && !isset($params['store_id'])) {
            $params['store_id'] = $this->_storeId;
        }

        // validate required parameter type or id
        if (!empty($params['type'])) {
            $type = $params['type'];
        } elseif (!empty($params['id'])) {
            $preConfigured = $this->_widgetResource->loadPreconfiguredWidget($params['id']);
            $type = $preConfigured['widget_type'];
            $params = $preConfigured['parameters'];
        } else {
            return '';
        }

        // we have no other way to avoid fatal errors for type like 'cms/widget__link', '_cms/widget_link' etc.
        $xml = $this->_widget->getWidgetByClassType($type);
        if ($xml === null) {
            return '';
        }

        // If widget is not CmsBlock let frontend handle it
        if ($params['type'] !== 'Magento\Cms\Block\Widget\Block') {
            return $this->widgetToHtml($params);
        }

        // define widget block and check the type is instance of Widget Interface
        $widget = $this->_layout->createBlock($type, $name, ['data' => $params]);
        if (!$widget instanceof \Magento\Widget\Block\BlockInterface) {
            return '';
        }

        return $widget->toHtml();
    }

    /**
     * Generates widget html-like instructions
     *
     * @param string[] $params
     * @return string
     */
    public function widgetToHtml($params)
    {
        unset($params['template']);

        $paramsList = [];
        foreach ($params as $key => $value) {
            if (!in_array($key, FilterEmulate::$widgetParamsWhitelist)) {
                $value = $this->_escaper->escapeHtmlAttr($value);
            }

            $paramsList[] = "$key='$value'";
        }

        $attributes = implode(' ', $paramsList);

        return "<widget $attributes></widget>";
    }
}
