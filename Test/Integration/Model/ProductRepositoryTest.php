<?php
/**
 * ProductRepositoryTest
 *
 * @copyright Copyright © 2017 Php4u Marcin Szterling. All rights reserved.
 * @author    marcin@php4u.co.uk
 */

namespace Php4u\WbTest\Test\Integration\Model;

/**
 * Product Repository Test
 *
 * @magentoAppIsolation enabled
 */
class ProductRepositoryTest extends \Magento\TestFramework\TestCase\WebapiAbstract
{
    const RESOURCE_PATH = '/V1/wb/products/';
    const CONFIGURABLE_SKU = 'configurable';

    /**
     * @magentoApiDataFixture Magento/ConfigurableProduct/_files/product_configurable.php
     */
    public function testExtraProductInformation()
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . self::CONFIGURABLE_SKU,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET
            ],
        ];

        $actual = $this->_webApiCall($serviceInfo, []);
        $this->assertArrayHasKey('extension_attributes', $actual, 'Product has no extension attributes');
        $this->assertArrayHasKey(
            'configurable_products_info',
            $actual['extension_attributes'],
            'configurable_products_info is not present in extension attributes'
        );
        $this->assertNotEmpty(
            $actual['extension_attributes']['configurable_products_info'],
            'Configurable product has no simple products'
        );
        $this->assertCount(
            2,
            $actual['extension_attributes']['configurable_products_info'],
            'Configurable product should have only 2 simple products'
        );

        $configurableProductInfo = $actual['extension_attributes']['configurable_products_info'];
        $this->remove($configurableProductInfo, ['value', 'id']);
        $expectedItems = [
            [
                'attributes' => [
                    [
                        'code' => 'test_configurable',
                        'label' => 'Option 1'
                    ]
                ],
                'sku' => 'simple_10'
            ],
            [
                'attributes' => [
                    [
                        'code' => 'test_configurable',
                        'label' => 'Option 2'
                    ]
                ],
                'sku' => 'simple_20'
            ]
        ];
        ksort($expectedItems);
        ksort($actual);
        $this->assertEquals($expectedItems, $configurableProductInfo);
    }

    /** helpers */

    /**
     * Remove key recursively
     * @param array $array
     * @param $keys
     * @return array
     */
    protected function remove(array &$array, $keys)
    {
        $keys = (is_array($keys)) ? $keys : [$keys];

        foreach ($array as $key => &$value) {
            if (in_array($key, $keys, true)) {
                unset($array[$key]);
            }

            if (is_array($value)) {
                $this->remove($value, $keys);
            }
        }

        return $array;
    }
}