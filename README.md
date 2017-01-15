[![Latest Unstable Version](https://poser.pugx.org/myparcelnl/sdk/v/unstable)](https://packagist.org/packages/myparcelnl/sdk)
# MyParcel SDK

---

- [Installation](#installation)
- [Installation without composer](#installation-without-composer)
- [Requirements](#requirements)
- [Quick start and examples](#quick-start-and-examples)
- [Available Methods](#available-methods)
- [Contribute](#contribute)

---

### Installation

This SDK uses composer.

Composer is a tool for dependency management in PHP. It allows you to declare the libraries your project depends on and it will manage (install/update) them for you.

For more information on how to use/install composer, please visit: [https://github.com/composer/composer](https://github.com/composer/composer)

To install the MyParcel SDK into your project, simply

	$ composer require myparcelnl/sdk
	
### Installation without composer

If you don't have experience with composer, it is possible to use the SDK without using composer.

You can download the zip on the projects [releases](https://github.com/myparcelnl/sdk/releases) page.

1. Download the package zip (SDKvx.x.x.zip).
2. Unzip the contents of the zip, and upload the vendor directory to your server.
3. In your project, require the file vendor/autoload.php
4. You can now use the SDK in your project

### Requirements

The MyParcel SDK works on php versions 5.6 and 7.0
Also the php curl extension needs to be installed.

### Quick start and examples

```php
$myParcelAPI = new MyParcelAPI();

$consignment = (new MyParcelConsignmentRepository())
    ->setApiKey('api_key_from_MyParcel_backoffice')
    ->setCountry('NL')
    ->setPerson('Piet Hier')
    ->setCompany('Piet BV')
    ->setFullStreet('Plein 1945 55b')
    ->setPostalCode('2231JE')
    ->setCity('Amsterdam')
    ->setEmail('test@test.nl');
    
$myParcelAPI
    ->addConsignment($consignment)
    ->setPdfOfLabels()
    ->downloadPdfOfLabels();
```

### Testing
Please run ```vendor/bin/phpunit --bootstrap vendor/autoload.php  tests/``` to test the application


### Available Methods
```php
$myParcelAPI = new MyParcelAPI();

$consignment = (new MyParcelConsignmentRepository())
    ->setApiKey('api_key_from_MyParcel_backoffice')
    ->setCountry('NL')
    ->setPerson('Piet Hier')
    ->setCompany('Piet BV')
    ->setFullStreet('Plein 1945 55b')
    ->setPostalCode('2231JE')
    ->setPackageType(1)
    ->setCity('Amsterdam')
    ->setEmail('test@test.nl')
    ->setPhone('+31 (0)634213465')
    ->setLargeFormat(false)
    ->setOnlyRecipient(false)
    ->setSignature(false)
    ->setReturn(false)
    ->setInsurance(false)
    ->setLabelDescription('Order 10034');
    
$myParcelAPI
    ->addConsignment($consignment)
```
```php
$myParcelAPI->createConcepts();
```
```php
$myParcelAPI->setPdfOfLabels();
$consignment = $myParcelAPI->getOneConsignment();
$consignment->getBarcode(); // You can save the barcode in your database
$consignment->getApiId(); // Keep this in your database. With this id you can easily retrieve the latest status.
$myParcelAPI->downloadPdfOfLabels();
```
```php
$myParcelAPI
    ->setLinkOfLabels()
    ->getLabelLink()
```
```php

$myParcelAPI
    ->setLatestData()
    ->getOneConsignment()
```

### Contribute
1. Check for open issues or open a new issue to start a discussion around a bug or feature.
1. Fork the repository on GitHub to start making your changes.
1. Write one or more tests for the new feature or that expose the bug.
1. Make code changes to implement the feature or fix the bug.
1. Send a pull request to get your changes merged and published.
