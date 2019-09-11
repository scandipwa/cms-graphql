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

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
use Magento\Framework\Escaper;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\View\LayoutInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Variable\Model\Source\Variables;
use Magento\Variable\Model\VariableFactory;
use Magento\Widget\Block\BlockInterface;
use Magento\Widget\Model\Widget;
use Pelago\Emogrifier;
use Psr\Log\LoggerInterface;

/**
 * Class FilterEmulate
 * @package ScandiPWA\CmsGraphQl\Model\Template
 */
class Filter extends \Magento\Widget\Model\Template\FilterEmulate
{

    /**
     * Array of keys that will not be escaped
     * in custom widget html output
     *
     * @var string[]
     */
    protected $widgetParamsWhitelist;

    protected $widgetCustomParamsHandlers;

    /**
     * Array of objects that will parsed to custom widget syntax
     *
     * @var object[]
     */
    public $availableFilters;

    public function __construct(
        StringUtils $string,
        LoggerInterface $logger,
        Escaper $escaper,
        Repository $assetRepo,
        ScopeConfigInterface $scopeConfig,
        VariableFactory $coreVariableFactory,
        StoreManagerInterface $storeManager,
        LayoutInterface $layout,
        LayoutFactory $layoutFactory,
        State $appState,
        UrlInterface $urlModel,
        Emogrifier $emogrifier,
        Variables $configVariables,
        \Magento\Widget\Model\ResourceModel\Widget $widgetResource,
        Widget $widget,
        array $availableFilters,
        array $widgetUnescapedParams,
        array $widgetCustomParamsHandlers
    ) {
        parent::__construct($string, $logger, $escaper, $assetRepo, $scopeConfig, $coreVariableFactory, $storeManager, $layout, $layoutFactory, $appState, $urlModel, $emogrifier, $configVariables, $widgetResource, $widget);
        $this->availableFilters = $availableFilters;
        $this->widgetParamsWhitelist = $widgetUnescapedParams;
        $this->widgetCustomParamsHandlers = $widgetCustomParamsHandlers;
    }

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

        if ($widgetName = array_search($params['type'], $this->availableFilters)) {
            return $this->widgetToHtml($params, $widgetName);
        }

        // define widget block and check the type is instance of Widget Interface
        $widget = $this->_layout->createBlock($type, $name, ['data' => $params]);
        if (!$widget instanceof BlockInterface) {
            return '';
        }

        return $widget->toHtml();
    }

    /**
     * Generates widget html-like instructions
     *
     * @param string[] $params
     * @param string $widgetName
     * @return string
     */
    public function widgetToHtml($params, $widgetName)
    {
        unset($params['template']);
        $params['type'] = $widgetName;

        $paramsList = [];
        foreach ($params as $key => $value) {
            if (key_exists($key, $this->widgetCustomParamsHandlers)) {
                $value = $this->widgetCustomParamsHandlers[$key]->resolve($value);
            } elseif (!in_array($key, $this->widgetParamsWhitelist)) {
                $value = $this->_escaper->escapeHtmlAttr($value);
            }

            $paramsList[] = "$key='$value'";
        }

        $attributes = implode(' ', $paramsList);

        return "<widget $attributes></widget>";
    }
}
