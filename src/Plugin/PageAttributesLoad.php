<?php

namespace ScandiPWA\CmsGraphQl\Plugin;

use Magento\Cms\Api\Data\PageExtensionInterface;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\Data\PageExtensionFactory;

class PageAttributesLoad
{
    /**
     * @var PageExtensionFactory
     */
    private $extensionFactory;

    /**
     * @param PageExtensionFactory $extensionFactory
     */
    public function __construct(PageExtensionFactory $extensionFactory)
    {
        $this->extensionFactory = $extensionFactory;
    }

    /**
     * Loads page entity extension attributes
     *
     * @param PageInterface $entity
     * @param PageExtensionInterface|null $extension
     * @return PageExtensionInterface
     */
    public function afterGetExtensionAttributes(
        PageInterface $entity,
        PageExtensionInterface $extension = null
    ) {
        if ($extension === null) {
            $extension = $this->extensionFactory->create();
        }

        return $extension;
    }
}
