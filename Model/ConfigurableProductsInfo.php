<?php
/**
 * ConfigurableProductsInfo
 *
 * @copyright Copyright © 2017 Php4u Marcin Szterling. All rights reserved.
 * @author    marcin@php4u.co.uk
 */

namespace Php4u\WbTest\Model;

/**
 * Class ConfigurableProductsInfo
 * @package Php4u\WbTest\Model
 */
class ConfigurableProductsInfo implements \Php4u\WbTest\Api\Data\ConfigurableProductsInfoInterface
{

    /**
     * @var string
     */
    protected $sku = '';

    /**
     * @var string
     */
    protected $id = '';

    /**
     * @var \Php4u\WbTest\Api\Data\ConfigurableOptionsInterface[]
     */
    protected $attributes = [];

    /**
     * @inheritdoc
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @inheritdoc
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @inheritdoc
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @inheritdoc
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

}