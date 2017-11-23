<?php
/**
 * ConfigurableOptionsProducts
 *
 * @copyright Copyright © 2017 Php4u Marcin Szterling. All rights reserved.
 * @author    marcin@php4u.co.uk
 */

namespace Php4u\WbTest\Api\Data;

/**
 * Interface ConfigurableOptionsInterface
 * @package Php4u\WbTest\Api\Data
 */
interface ConfigurableOptionsInterface
{
    /**
     * @param string $code
     * @return void
     */
    public function setCode($code);

    /**
     * @param string $label
     * @return void
     */
    public function setLabel($label);

    /**
     * @param string $value
     * @return void
     */
    public function setValue($value);

    /**
     * @return string
     */
    public function getCode();

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return string
     */
    public function getValue();
}