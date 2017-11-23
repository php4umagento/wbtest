<?php
/**
 * ProductRepository
 *
 * @copyright Copyright © 2017 Php4u Marcin Szterling. All rights reserved.
 * @author    marcin@php4u.co.uk
 * @package   Php4u\WbTest\Model
 */

namespace Php4u\WbTest\Model;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class ProductRepository
 */
class ProductRepository implements \Php4u\WbTest\Api\ProductRepositoryInterface
{

    /**
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * @var Product[]
     */
    protected $instances = [];

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    protected $resourceModel;

    /**
     * @var int
     */
    protected $cacheLimit = 0;

    /**
     * @var \Php4u\WbTest\Api\Data\ConfigurableOptionsInterfaceFactory
     */
    protected $configurableOptionsFactory;

    /**
     * @var \Php4u\WbTest\Api\Data\ConfigurableProductsInfoInterfaceFactory
     */
    protected $configurableProductsInfoFactory;

    /**
     * ProductRepository constructor.
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product $resourceModel
     * @param \Php4u\WbTest\Api\Data\ConfigurableOptionsInterfaceFactory $configurableOptionsFactory
     * @param \Php4u\WbTest\Api\Data\ConfigurableProductsInfoInterfaceFactory $configurableProductsInfoFactory
     * @param int $cacheLimit
     */
    public function __construct(
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\ResourceModel\Product $resourceModel,
        \Php4u\WbTest\Api\Data\ConfigurableOptionsInterfaceFactory $configurableOptionsFactory,
        \Php4u\WbTest\Api\Data\ConfigurableProductsInfoInterfaceFactory $configurableProductsInfoFactory,
        $cacheLimit = 1000
    ) {
        $this->productFactory = $productFactory;
        $this->resourceModel = $resourceModel;
        $this->cacheLimit = (int)$cacheLimit;
        $this->configurableOptionsFactory = $configurableOptionsFactory;
        $this->configurableProductsInfoFactory = $configurableProductsInfoFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function get($sku, $editMode = false, $storeId = null, $forceReload = false)
    {
        $cacheKey = $this->getCacheKey([$editMode, $storeId]);
        if (!isset($this->instances[$sku][$cacheKey]) || $forceReload) {
            $product = $this->productFactory->create();
            /* @var $product \Magento\Catalog\Model\Product */

            $productId = $this->resourceModel->getIdBySku($sku);
            if (!$productId) {
                throw new NoSuchEntityException(__('Requested product doesn\'t exist'));
            }
            if ($editMode) {
                $product->setData('_edit_mode', true);
            }
            if ($storeId !== null) {
                $product->setData('store_id', $storeId);
            }
            $product->load($productId);
            $this->setConfigurableOptions($product);
            $this->cacheProduct($cacheKey, $product);
        }
        if (!isset($this->instances[$sku])) {
            $sku = trim($sku);
        }
        return $this->instances[$sku][$cacheKey];
    }

    /**
     * This method adds extra information about child SKUs
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return void
     */
    protected function setConfigurableOptions(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        if ($product->getTypeId() == 'configurable') {
            $productType = $product->getTypeInstance();
            $attributes = $productType->getConfigurableAttributesAsArray($product);
            $configurableProductInfoArray = [];
            foreach ($productType->getUsedProducts($product) as $childProduct) {
                $configurableProductInfo = $this->configurableProductsInfoFactory->create();
                /* @var $configurableProductInfo \Php4u\WbTest\Api\Data\ConfigurableProductsInfoInterface */

                $attributesValues = [];
                foreach ($attributes as $attribute) {
                    $attributeValue = $childProduct->getData($attribute['attribute_code']);
                    $attrObj = $childProduct->getResource()->getAttribute($attribute['attribute_code']);

                    $attributeOptionRepresentation = $this->configurableOptionsFactory->create();
                    /* @var \Php4u\WbTest\Api\Data\ConfigurableOptionsInterface $attributeOptionRepresentation */
                    $attributeOptionRepresentation->setCode($attribute['attribute_code']);
                    $attributeOptionRepresentation->setLabel(
                        $attrObj->usesSource() ? $attrObj->getSource()->getOptionText($attributeValue) : $attributeValue
                    );
                    $attributeOptionRepresentation->setValue($attributeValue);

                    $attributesValues[] = $attributeOptionRepresentation;
                }

                $configurableProductInfo->setSku($childProduct->getSku());
                $configurableProductInfo->setId($childProduct->getId());
                $configurableProductInfo->setAttributes($attributesValues);

                $configurableProductInfoArray[] = $configurableProductInfo;
            }

            $extensionAttributes = $product->getExtensionAttributes();
            if ($extensionAttributes !== null) {
                $extensionAttributes->setConfigurableProductsInfo($configurableProductInfoArray);
                $product->setExtensionAttributes($extensionAttributes);
            }
        }
    }

    /**
     * Get key for cache
     *
     * @param array $data
     * @return string
     */
    protected function getCacheKey($data)
    {
        $serializeData = [];
        foreach ($data as $key => $value) {
            if (is_object($value)) {
                $serializeData[$key] = $value->getId();
            } else {
                $serializeData[$key] = $value;
            }
        }

        return md5(serialize($serializeData));
    }

    /**
     * Add product to internal cache and truncate cache if it has more than cacheLimit elements.
     *
     * @param string $cacheKey
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return void
     */
    private function cacheProduct($cacheKey, \Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $this->instances[$product->getSku()][$cacheKey] = $product;

        if ($this->cacheLimit && count($this->instances) > $this->cacheLimit) {
            $offset = round($this->cacheLimit / -2);
            $this->instances = array_slice($this->instances, $offset);
        }
    }

}
