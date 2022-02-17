<?php
/**
 * ScandiPWA_CmsGraphQl
 *
 * @category    Scandiweb
 * @package     ScandiPWA_CmsGraphQl
 * @copyright   Copyright (c) 2022 Scandiweb, Ltd (https://scandiweb.com)
 */
declare(strict_types=1);

namespace ScandiPWA\CmsGraphQl\Plugin;

use Exception;
use Magento\Framework\App\Area;
use Magento\Framework\App\FrontControllerInterface;
use Magento\Framework\App\AreaList;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use ScandiPWA\PersistedQuery\Plugin\PersistedQuery;

/**
 * Class InitGraphQlTranslations
 *
 * A fix for https://github.com/magento/magento2/issues/31351
 *
 * @see \Magento\Framework\App\Action\Plugin\Design
 * @see \Magento\Webapi\Controller\Soap
 * @package ScandiPWA\CmsGraphQl\Plugin
 */
class InitGraphQlTranslations
{
    /**
     * Application
     *
     * @var AreaList
     */
    protected AreaList $areaList;

    /**
     * State
     *
     * @var State
     */
    protected State $appState;

    /**
     * @param AreaList $areaList
     * @param State $appState
     */
    public function __construct(
        AreaList $areaList,
        State $appState
    ) {
        $this->areaList = $areaList;
        $this->appState = $appState;
    }

    /**
     * @param FrontControllerInterface $subject
     * @param RequestInterface $request
     *
     * @return void
     * @throws Exception
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeDispatch(
        FrontControllerInterface $subject,
        RequestInterface $request
    ) {
        $this->initTranslations();
    }

    /**
     * @param PersistedQuery $subject
     * @param RequestInterface $request
     * @return void
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeProcessRequest(
        PersistedQuery $subject,
        RequestInterface $request
    ) {
        $this->initTranslations();
    }

    /**
     * @return void
     */
    protected function initTranslations() {
        $area = $this->areaList->getArea($this->appState->getAreaCode());

        if ($area) {
            $area->load(Area::PART_TRANSLATE);
        }
    }
}
