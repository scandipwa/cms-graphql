<?php
/**
 * ScandiPWA_CmsGraphQl
 *
 * @category  Scandiweb
 * @package   ScandiPWA_CmsGraphQl
 * @author    Kriss Andrejevs <info@scandiweb.com>
 * @copyright Copyright (c) 2018 Scandiweb, Ltd (https://scandiweb.com)
 */
namespace ScandiPWA\CmsGraphQl\Model\Resolver;
use Magento\CmsGraphQl\Model\Resolver\DataProvider\Page as PageDataProvider;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\Resolver\ValueFactory;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Cms\Model\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
/**
 * Class Page
 * @package ScandiPWA\CmsGraphQl\Model\Resolver
 */
class Page extends \Magento\CmsGraphQl\Model\Resolver\Page
{
    /**
     * @var PageDataProvider
     */
    private $pageDataProvider;
    /**
     * @var ValueFactory
     */
    private $valueFactory;
    /**
     * @var PageFactory
     */
    private $pageFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @param PageDataProvider $pageDataProvider
     * @param ValueFactory $valueFactory
     */
    public function __construct(
        PageDataProvider $pageDataProvider,
        ValueFactory $valueFactory,
        PageFactory $pageFactory,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct(
            $pageDataProvider
        );
        $this->pageDataProvider = $pageDataProvider;
        $this->valueFactory = $valueFactory;
        $this->pageFactory = $pageFactory;
        $this->storeManager = $storeManager;
    }
    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) : Value {
        $result = function () use ($args) {
            $pageId = $this->getPageId($args);
            $pageData = $this->getPageData($pageId);
            return $pageData;
        };
        return $this->valueFactory->create($result);
    }
    /**
     * @param array $args
     * @return int
     * @throws GraphQlInputException
     */
    private function getPageId(array $args): int
    {
        $storeId = $this->storeManager->getStore()->getId();
        if (isset($args['id'])) {
            return (int)$args['id'];
        }
        if (isset($args['url_key'])) {
            return (int)$this->pageFactory->create()->setStoreId($storeId)->load($args['url_key'])->setStoreId($storeId)->getId();
        }
        throw new GraphQlInputException(__('Page id should be specified'));
    }
    /**
     * @param int $pageId
     * @return array
     * @throws GraphQlNoSuchEntityException
     */
    private function getPageData(int $pageId): array
    {
        try {
            $pageData = $this->pageDataProvider->getData($pageId);
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
        return $pageData;
    }
}
