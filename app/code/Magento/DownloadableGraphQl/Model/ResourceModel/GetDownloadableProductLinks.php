<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\DownloadableGraphQl\Model\ResourceModel;

use Magento\Catalog\Model\Product;
use Magento\Downloadable\Model\LinkFactory;
use Magento\Downloadable\Model\ResourceModel\Link\Collection;

/**
 * Class GetDownloadableProductLinks
 *
 * Returns links of a particular downloadable product
 */
class GetDownloadableProductLinks
{
    /**
     * @var LinkFactory
     */
    private $linkFactory;

    /**
     * GetDownloadableProductLinks constructor.
     *
     * @param LinkFactory $linkFactory
     */
    public function __construct(
        LinkFactory $linkFactory
    ) {
        $this->linkFactory = $linkFactory;
    }

    /**
     * Returns downloadable product links
     *
     * @param Product $product
     * @param array $selectedLinksIds
     * @return array
     */
    public function execute(Product $product, array $selectedLinksIds = []): array
    {
        /** @var Collection */
        $links = $this->linkFactory->create()->getResourceCollection();
        $links->addTitleToResult($product->getStoreId())
            ->addPriceToResult($product->getStore()->getWebsiteId())
            ->addProductToFilter($product->getId());

        if ($product->getLinksPurchasedSeparately() && count($selectedLinksIds) > 0) {
            $links->addFieldToFilter('main_table.link_id', ['in' => $selectedLinksIds]);
        }

        $result = [];
        foreach ($links as $link) {
            $result[] = $link;
        }

        return $result;
    }
}
