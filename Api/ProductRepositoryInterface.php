<?php
/**
 * @copyright Copyright © 2017 Php4u Marcin Szterling. All rights reserved.
 * @author    marcin@php4u.co.uk
 */

namespace Php4u\WbTest\Api;

/**
 * Interface ProductRepositoryInterface
 * @package Php4u\WbTest\Api
 */
interface ProductRepositoryInterface
{
    /**
     * Get info about product by product SKU
     *
     * @param string $sku
     * @param bool $editMode
     * @param int|null $storeId
     * @param bool $forceReload
     * @return \Magento\Catalog\Api\Data\ProductInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($sku, $editMode = false, $storeId = null, $forceReload = false);
}
