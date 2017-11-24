# Wb Test

You are tasked to create an optimized web service method for Magento 2 Community Edition that will return a product with all of its child sku options in a single response. 

## Approach

My approach is to use extension attributes to inject extra information into product interface

## Installation

```bash
composer config repositories.wbtest git https://github.com/php4umagento/wbtest.git
composer require php4u/module-wbtest
```

Then recompile and flush magento cache

## API endpoint 

API endpoint is http(s)://{{magento_url}}/rest/V1/wb/products/{SKU}

Response:
```
{
  "id": 0,
  "sku": "string",
  "name": "string",
  ...
  "extension_attributes": {
    "configurable_products_info": [
      {
        "sku": "string",
        "id": 0,
        "attributes": [
          {
            "code": "string",
            "label": "string",
            "value": "string"
          }
        ]
      }
    ]
  },
  ...
  "custom_attributes": [
    {
      "attribute_code": "string",
      "value": "string"
    }
  ]
}
```

Sample response can be found [here](https://cdn.rawgit.com/php4umagento/wbtest/c73e9d59/doc/extraProductInformation.html)

## Swagger documentation

Swagger documentation is accessible via url http(s)://{{magento_url}}/swagger#!/php4uWbTestProductRepositoryV1/php4uWbTestProductRepositoryV1GetGet