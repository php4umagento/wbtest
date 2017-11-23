<?php
/**
 * ConfigurableOptionsProducts
 *
 * @copyright Copyright © 2017 Php4u Marcin Szterling. All rights reserved.
 * @author    marcin@php4u.co.uk
 */

namespace Php4u\WbTest\Api\Data;

/**
 * Interface ConfigurableProductsInfoInterface
 * @package Php4u\WbTest\Api\Data
 */
interface ConfigurableProductsInfoInterface
{
    /**
     * @param string $sku
     * @return void
     */
    public function setSku($sku);

    /**
     * @param integer $id
     * @return void
     */
    public function setId($id);

    /**
     * @param \Php4u\WbTest\Api\Data\ConfigurableOptionsInterface[] $attributes
     * @return void
     */
    public function setAttributes(array $attributes);

    /**
     * @return string
     */
    public function getSku();

    /**
     * @return integer
     */
    public function getId();

    /**
     * @return \Php4u\WbTest\Api\Data\ConfigurableOptionsInterface[]
     */
    public function getAttributes();

}