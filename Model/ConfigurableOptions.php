<?php
/**
 * ConfigurableOptions
 *
 * @copyright Copyright © 2017 Php4u Marcin Szterling. All rights reserved.
 * @author    marcin@php4u.co.uk
 */

namespace Php4u\WbTest\Model;

/**
 * Class ConfigurableOptions
 * @package Php4u\WbTest\Model
 */
class ConfigurableOptions implements \Php4u\WbTest\Api\Data\ConfigurableOptionsInterface
{

    /**
     * @var string
     */
    protected $code = '';

    /**
     * @var string
     */
    protected $label = '';

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @inheritdoc
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @inheritdoc
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @inheritdoc
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @inheritdoc
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

}