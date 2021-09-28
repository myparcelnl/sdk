<a href="https://packagist.org/packages/myparcelnl/sdk" target="_blank"><img src="https://img.shields.io/packagist/dt/myparcelnl/sdk" alt="Total Downloads"></a>
<a href="https://github.com/myparcelnl/sdk/releases" target="_blank"><img src="https://img.shields.io/packagist/v/myparcelnl/sdk" alt="Latest Stable Version"></a>
<a href="https://join.slack.com/t/myparcel-dev/shared_invite/enQtNDkyNTg3NzA1MjM4LTM0Y2IzNmZlY2NkOWFlNTIyODY5YjFmNGQyYzZjYmQzMzliNDBjYzBkOGMwYzA0ZDYzNmM1NzAzNDY1ZjEzOTM" target="_blank"><img src="https://img.shields.io/badge/Slack-Chat%20with%20us-white?logo=slack&labelColor=blue" alt="Slack support"></a>	

# MyParcel SDK
This SDK connects to the MyParcel API using PHP.

Do you want to be kept informed of new functionalities or do you just need help? You can contact us via our [Slack channel](https://join.slack.com/t/myparcel-dev/shared_invite/enQtNDkyNTg3NzA1MjM4LTM0Y2IzNmZlY2NkOWFlNTIyODY5YjFmNGQyYzZjYmQzMzliNDBjYzBkOGMwYzA0ZDYzNmM1NzAzNDY1ZjEzOTM).

## Contents

- [Contents](#contents)
- [Installation](#installation)
    - [Requirements](#requirements)
    - [Installation with Composer](#installation-with-composer)
    - [Installation without Composer](#installation-without-composer)
- [Quick start and examples](#quick-start-and-examples)
    - [Create a consignment](#create-a-consignment)
        - [Using multiple carriers](#using-multiple-carriers)
    - [Create multiple consignments](#create-multiple-consignments)
    - [Create return in the box](#create-return-in-the-box)
    - [Label format and position](#label-format-and-position)
    - [Package type and options](#package-type-and-options)
    - [Find consignments](#find-consignments)
    - [Query consignments](#query-consignments)
    - [Retrieve data from a consignment](#retrieve-data-from-a-consignment)
    - [Create and download label(s)](#create-and-download-labels)
    - [Orders](#orders)
      - [Create and save orders](#create-and-save-orders)
    - [Account settings](#account-settings)
    - [Filling in required drop off point for RedJePakketje](#filling-in-required-drop-off-point-for-redjepakketje)
- [List of classes and their methods](#list-of-classes-and-their-methods)
    - [Models](#models)
    - [Helpers](#helpers)
    - [Delivery options from the checkout with adapters](#delivery-options-from-the-checkout-with-adapters)
- [Tips](#tips)
- [Contribute](#contribute)

## Installation

### Requirements
The MyParcel SDK works with PHP version >= 7.1.0.

If you have a php version lower than 7.1.0 then we would like to advise you to update your PHP version to a [supported versions](https://www.php.net/supported-versions.php).
For support for PHP version 5.6 you can use release [2.x.](https://github.com/myparcelnl/sdk/releases).

### Installation with Composer
This SDK uses Composer. Composer is a tool for dependency management in PHP. It allows you to declare the libraries your project depends on and it will manage (install/update) them for you. For more information on how to use/install composer, please visit https://getcomposer.org/

To install the MyParcel SDK into your project, simply use
```
$ composer require myparcelnl/sdk
```
	
### Installation without Composer
It's also possible to use the SDK without installing it with Composer.

You can download the zip on https://github.com/myparcelnl/sdk/archive/master.zip.

1. [Download the package](https://github.com/myparcelnl/sdk/archive/master.zip).
2. Extract the downloaded .zip file and upload it to your server.
3. Require `src/AutoLoader.php`
4. You can now use the SDK in your project!

## Quick start and examples
Add the following lines to your project to import the SDK classes for creating shipments.
```php
use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;
```

### Create a consignment
This example uses only the required methods to create a shipment and download its label.

```php
$consignment = (ConsignmentFactory::createByCarrierId(PostNLConsignment::CARRIER_ID))
    ->setApiKey('api_key_from_backoffice')
    ->setReferenceIdentifier('order-146')
    ->setCountry('NL')
    ->setPerson('Piet Hier')
    ->setFullStreet('Plein 1945 55b')
    ->setPostalCode('2231JE')
    ->setCity('Amsterdam')
    ->setEmail('piet.hier@test.nl')
    ->setLabelDescription('Order 146');
    
$consignments = (new MyParcelCollection())
    ->addConsignment($consignment)
    ->setPdfOfLabels();

$consignmentId = $consignments->first()->getConsignmentId();

$consignments->downloadPdfOfLabels();
```

### Using multiple carriers
It is possible to use multiple carriers. The following carriers are supported:

| Name          | Carrier class                        | Consignment class                            | 
| ---           | ---                                  | ---                                          | 
| PostNL        | `Model\Carrier\CarrierPostNL`        | `Model\Consignment\PostNLConsignment`        | 
| Bpost         | `Model\Carrier\CarrierBpost`         | `Model\Consignment\BpostConsignment`         | 
| DPD           | `Model\Carrier\CarrierDPD`           | `Model\Consignment\DPDConsignment`           | 
| RedJePakketje | `Model\Carrier\CarrierRedJePakketje` | `Model\Consignment\RedJePakketjeConsignment` | 

Please note, RedJePakketje is only available in the Netherlands as of now, and can only ship to Dutch addresses.
You can query which carriers are available like so:

```php
$options = (new CarrierOptionsWebService())
    ->setApiKey('api_key_from_backoffice')
    ->getCarrierOptions('shop_id');
```

You can get the required shop_id from the accounts webservice, the only shop returned will be the shop belonging to the api key that is used.
Since the shop_id will not change unless the api key changes, we advise you to store the shop_id until the user updates the api key.
```php
$shop_id = (new AccountWebService())
    ->setApiKey('api_key_from_backoffice')
    ->getAccount()
    ->getShops()
    ->first()
    ->getId();
```

#### Create a consignment for each carrier manually:
```php
$postNLConsignment        = new \MyParcelNL\Sdk\Model\Consignment\PostNLConsignment();
$bpostConsignment         = new \MyParcelNL\Sdk\Model\Consignment\BpostConsignment();
$dpdConsignment           = new \MyParcelNL\Sdk\Model\Consignment\DPDConsignment();
$redJePakketjeConsignment = new \MyParcelNL\Sdk\Model\Consignment\RedJePakketjeConsignment();
```

Or by using `\MyParcelNL\Sdk\src\Factory\ConsignmentFactory` with a carrier id or name, allowing for it to be dynamic:
```php
use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierBpost;use MyParcelNL\Sdk\src\Model\Carrier\CarrierPostNL;

$postNLConsignment = ConsignmentFactory::createByCarrierId(CarrierPostNL::getId());
$bpostConsignment  = ConsignmentFactory::createByCarrierName(CarrierBpost::getName());
```

### Create multiple consignments
This example creates multiple consignments by adding them to one ```MyParcelCollection()``` and then creates and downloads one PDF with all labels.

```php
// Create the collection before the loop
$consignments = (new MyParcelCollection())
    ->setUserAgents(['name_of_cms', '1.0']); 

// Loop through your shipments, adding each to the same MyParcelCollection()
foreach ($yourShipments as $yourShipment) {

    $consignment = ((ConsignmentFactory::createByCarrierId(PostNLConsignment::CARRIER_ID))
        ->setApiKey('api_key_from_backoffice')
        ->setReferenceIdentifier($yourShipment['reference_id'])
        ->setPerson($yourShipment['name'])
        ->setPostalCode($yourShipment['postal_code'])
        ->setFullStreet($yourShipment['full_street']) 
        ->setCity($yourShipment['city'])
    );
        
    // Add each consignment to the collection created before
    $consignments
        ->addConsignment($consignment);
}
```

### Create return in the box
This example creates a consignment and a related return consignment by adding them to one `MyParcelCollection()` and then creates and downloads a single PDF file with both labels.
```php
// Create the collection before the loop
$consignments = new MyParcelCollection();

// Loop through your shipments, adding each to the same MyParcelCollection()
foreach ($yourShipments as $yourShipment) {

    $consignment = ((ConsignmentFactory::createByCarrierId(PostNLConsignment::CARRIER_ID))
        ->setApiKey('api_key_from_backoffice')
        ->setCountry($yourShipment['cc'])
        ->setPerson($yourShipment['person'])
        ->setCompany($yourShipment['company'])
        ->setFullStreet($yourShipment['full_street_input'])
        ->setPostalCode($yourShipment['postal_code'])
        ->setCity($yourShipment['city'])
        ->setLabelDescription($yourShipment['label_description'])
    );
        
    // Add the consignment to the collection and generate the return consignment
    // When there are no options set, the options from the parent consignment are used
    $consignments
        ->addConsignment($consignment)
        ->generateReturnConsignments(
            false,
            function (
                AbstractConsignment $returnConsignment,
                AbstractConsignment $parent
            ): AbstractConsignment {
                $returnConsignment->setLabelDescription(
                    'Return: ' . $parent->getLabelDescription() .
                    ' This label is valid until: ' . date("d-m-Y", strtotime("+ 28 days"))
                );
                $returnConsignment->setSignature(true);

                return $returnConsignment;
            }
        );
}
```

### Label format and position
Choose to output the label as either A4 or A6 when creating a pdf or download link with the argument `$positions` of `setPdfOfLabels($positions)` and `setLinkOfLabels($positions)`.

Example values for `$positions`:
```
A4:            A6:
┏━━━━━┳━━━━━┓  ┏━━━━━┓
┃  1  ┃  2  ┃  ┃  x  ┃
┣━━━━━╋━━━━━┫  ┗━━━━━┛
┃  3  ┃  4  ┃
┗━━━━━┻━━━━━┛  
```
1. `1`: Default value. Outputs A4, starting at top left position.
1. `false`: Outputs at A6 format
1. `[1,4]`: Defines the position of labels on an A4 sheet. Only applies to the first page, subsequent pages will use the default positioning (1,2,3,4)

More information: https://myparcelnl.github.io/api/#6_F

### Package type and options
Set package type with `setPackageType($type)`. Retrieve it after with `getPackageType()`. For more details on the different types of packages: https://myparcelnl.github.io/api/#6_A_1

#### 1: Package
This is the default package type. It must be explicitly set to allow enabling of further shipment options. It's available for NL, EU and global shipments.

#### 2: Mailbox package
This package type is only available for NL shipments that fit into a mailbox. It does not support additional options.
Note: If you still make the request with additional options, bear in mind that you need to pay more than is necessary!

#### 3: Letter 
This package type is available for NL, EU and global shipments. The label for this shipment is unpaid meaning that you will need to pay the postal office/courier to send this letter/package. Therefore, it does not support additional options.

#### 4: Digital stamp 
This package type is only available for NL shipments and does not support any additional options. Its price is calculated using the package weight, which is set using `setPhysicalProperties()`.

```php
    ->setPackageType(4)
    ->setPhysicalProperties(['weight' => 300]); // weight in grams (required)
```

> Note: This shipment will appear on your invoice on shipment_status 2 (pending - registered) instead of all other shipment types, which don't appear until shipment status 3. Read more: https://myparcelnl.github.io/api/#6_A_1

#### Package options
These options are only available for package type 1 (package).

Available options:
- only_recipient: Deliver the package only at address of the intended recipient. This option is required for Morning and Evening delivery types.
  - Set: `setOnlyRecipient(true)`
  - Get: `isOnlyRecipient()`
- signature: Recipient must sign for the package. This option is required for Pickup delivery type.
  - Set: `setSignature(true)`
  - Get: `isSignature()`
- return: Return the package to the sender when the recipient is not home.
  - Set: `setReturn(true)`
  - Get: `isReturn()`
- large_format: This option must be specified if the dimensions of the package are between 100 x 70 x 50 and 175 x 78 x 58 cm. If the scanned dimensions from the carrier indicate that this package is large format and it has not been specified then it will be added to the shipment in the billing process. This option is also available for EU shipments.
  - Set: `setLargeFormat(true)`
  - Get: `isLargeFormat()`
- age_check: The Customer/Consumer must sign for the package and only receive it when he is at least 18 years.
    - Set: `setAgeCheck(true)`
    - Get: `hasAgeCheck()`
- insurance: This option allows a shipment to be insured up to certain amount. NL shipments can be insured for 5000,- euros. EU shipments must be insured for 500,- euros. Global shipments must be insured for 200,- euros. The following shipment options are mandatory when insuring an NL shipment: only_recipient and signature.
  - Set: `setInsurance(250)` (amount in EUR)
  - Get: `getInsurance()`

More information: https://myparcelnl.github.io/api/#6_A_3

### Find consignments
After creating consignments, it is often necessary to pick up a specific consignment:
```php
$consignments = MyParcelCollection::find(432345);
```
Instead of `find()` you can also use `findMany()`, `findByReferenceIdentifier()` or `findManyByReferenceIdentifier()`.

For `reference identifier` you can use a `*` to search smarter:
```php
$consignments = MyParcelCollection::findByReferenceIdentifier('order-14*');
```

### Query consignments
You can search and filter consignments by certain values:
```php
$consignments = MyParcelCollection::query(
            'api_key_from_backoffice',
            [
                'q'                    => 'Niels',
                'reference_identifier' => 'order-1234',
                'status'               => 2,
                'from'                 => '2020-01-01 00:00:00',
                'to'                   => '2020-02-01 00:00:00',
                'page'                 => 1,
                'size'                 => 200,
                'order'                => 'DESC',
                'package_type'         => 1,
                'region'               => 'NL;EU',
                'dropoff_today'        => 1,
            ]
        )
```
For `q` and `reference identifier` you can use `*` to search smarter.
> If the 2nd parameter is an object, then public properties will be used. If you query in many ways, creating a separate class can provide a clean solution.

More information: https://myparcelnl.github.io/api/#6_E.

### Retrieve data from a consignment
Most attributes that have a set...() method also have a get...() method to retrieve the data. View [all methods](#PostNLConsignment) for consignments here. 
```php
$consignment = new PostNLConsignment();

echo $consignment->getFullStreet();
echo $consignment->getPerson();
echo $consignment->getPhone();
echo $consignment->getStreet();
// etc...
```

#### Get status
After ```setPdfOfLabels()```, ```setLinkOfLabels()``` and ```createConcepts()``` you can get the status.
```php
$status = $consignment->getStatus();
```

#### Get barcode
The barcode is available after ```setPdfOfLabels()``` and ```setLinkOfLabels()```
```php
$barcode = $consignment->getBarcode();
```

#### Get Track & Trace url
The Track & Trace url is available after `downloadPdfOfLabels()` and `getLinkOfLabels()`
```php
$consignment = (new \MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment());
echo $consignment->getBarcodeUrl('3SMYPA123456789', '2231JE', 'NL'); // Barcode , Postal code, Country
```

### Create and download label(s)
Create and directly download PDF with `setPdfOfLabels($position)` where `$positions` is the [label position](#label-format-and-position) value. 
```php
$consignments
    ->setPdfOfLabels()
    ->downloadPdfOfLabels(false); // Opens pdf "inline" by default, pass false as argument to download file  
```

Create and echo download link to PDF with `setLinkOfLabels($position)` where `$positions` is the [label position](#label-format-and-position) value.
If you want more than 25 labels in one response, the setLinkOfLabels will automatically use a different endpoint. At that point, it is likely that the PDF is not ready yet. You should check periodically if the PDF is ready for download.
```php
echo $consignments 
    ->setLinkOfLabels($positions)
    ->getLinkOfLabels();
```

If you want to download a label at a later time, you can also use the following to fill the collection:
```php
$consignments = MyParcelCollection::findByReferenceIdentifier('order-146', 'api_key_from_backoffice');
$consignments
    ->setPdfOfLabels()
    ->downloadPdfOfLabels();
```
Instead of `findByReferenceIdentifier()` you can also use `findManyByReferenceIdentifier()`, `find()` or `findMany()`.

More information: https://myparcelnl.github.io/api/#6_F

### Orders

#### Create and save orders
Start by creating an `OrderCollection` and setting your API key:
```php
use MyParcelNL\Sdk\src\Collection\Fulfilment\OrderCollection;

$orderCollection = (new OrderCollection())
    ->setApiKey('api_key_from_backoffice');
```

Define the billing and shipping addresses of the order with the `Recipient` class.
```php
use MyParcelNL\Sdk\src\Model\Recipient;

$address = (new Recipient())
    ->setCompany('MyParcel')
    ->setPerson('Ms. Parcel')
    ->setEmail('ms@myparcel.nl')
    ->setPhone('0233030315')
    // Note: This field includes house number and number suffix.
    ->setStreet('Antareslaan 31')
    ->setPostalCode('2132JE')
    ->setCity('Hoofddorp')
    ->setCc('NL');
```

Create an `Order`:
```php
use MyParcelNL\Sdk\src\Model\Fulfilment\Order;

$order = (new Order())
    // The unique identifier of the order in your webshop.
    ->setExternalIdentifier('set_external_identifier')
    // Shipping address of the customer.
    ->setRecipient($shippingAddress)
    // Invoice/billing address of the customer.
    ->setInvoiceAddress($billingAddress)
    // The delivery options. Must be an instance of AbstractDeliveryOptionsAdapter.
    ->setDeliveryOptions($deliveryOptions)
    // The date when the order was placed. Can be a DateTime object or a string in Y-M-d H:i:s format.
    ->setOrderDate(new DateTime());
```

To create order lines, you'll probably want to loop over the order lines from the order in your webshop. Every Order should contain one or more order lines.
```php
use MyParcelNL\Sdk\src\Model\Fulfilment\OrderLine;

$orderLine = (new OrderLine())
    // Full price in cents, regardless of quantity, excluding VAT.
    ->setPrice(1972)
    // Full price in cents, regardless of quantity, including VAT.
    ->setPriceAfterVat(2495)
    // VAT in cents.
    ->setVat(523)
    // Amount of Products in this OrderLine.
    ->setQuantity(3)
    // Arbitrary key/value instructions. Currently unused.
    ->setInstructions([
        'wrapping' => 'the blue one with kittens on it'
    ]);
```

An order line stores its product data in `Product`. You can set product data in two different ways:

By getting the `Product` and setting properties on there. When an `OrderLine` is created an empty `Product` is always created:
```php
$orderLine->getProduct()
     // Product name.
    ->setName('Long Sleeved Button Up Shirt')
    // Additional product description.
    ->setDescription('White and blue')
    // Stock Keeping Unit of the product.
    ->setSku('MYPA-0012345')
    // The unique identifier of the product in your webshop software.
    ->setExternalIdentifier('#0001394')
    // Weight in grams
    ->setWeight(0)
    // Height in millimeters
    ->setHeight(0)
    // Length in millimeters
    ->setLength(0)
    // Width in millimeters
    ->setWidth(0);
```

Or by creating a `Product` separately and using `OrderLine::setProduct()`.
```php
use MyParcelNL\Sdk\src\Model\Fulfilment\Product;

$product = new Product();
// You can use the same setters from the example above.

$orderLine->setProduct($product);
```

Finally, add the created OrderLine to the order:
```php
$order->getOrderLines()->push($orderLine);
```

And add the order to the OrderCollection
```php
$orderCollection->push($order);
```

When the order is complete, save it to our API by using the `save` method.
```php
$savedOrderCollection = $orderCollection->save();
```

##### Full example
```php
use MyParcelNL\Sdk\src\Collection\Fulfilment\OrderCollection;
use MyParcelNL\Sdk\src\Model\Fulfilment\Order;
use MyParcelNL\Sdk\src\Model\Fulfilment\OrderLine;
use MyParcelNL\Sdk\src\Model\Recipient;

$orderCollection = (new OrderCollection())
    ->setApiKey('api_key_from_backoffice');

$customerAddress = (new Recipient())
    ->setCompany('MyParcel')
    ->setPerson('Ms. Parcel')
    ->setEmail('ms@myparcel.nl')
    ->setPhone('0233030315')
    ->setStreet('Antareslaan 31')
    ->setPostalCode('2132JE')
    ->setCity('Hoofddorp')
    ->setCc('NL');

$order = (new Order())
    ->setExternalIdentifier('set_external_identifier')
    ->setFulfilmentPartnerIdentifier('set_fulfilment_partner_identifier')
    ->setRecipient($customerAddress)
    ->setInvoiceAddress($customerAddress)
    ->setDeliveryOptions($deliveryOptions)
    ->setOrderDate(new DateTime());

foreach ($orderLines as $orderLineData) {
    $orderLine = (new OrderLine())
        ->setPrice(1972)
        ->setPriceAfterVat(2495)
        ->setVat(523)
        ->setQuantity(3)
        ->setInstructions([
            'wrapping' => 'the blue one with kittens on it'
        ]);
        
    $orderLine->getProduct()
        ->setWeight(10)
        ->setHeight(4)
        ->setLength(12)
        ->setWidth(100);

    $order->getOrderLines()->push($orderLine);
}

$orderCollection->push($order);

$savedOrderCollection = $orderCollection->save();
```

### Account settings

Retrieve Account and Shop settings, as well as carrier options and configurations using the supplied webservices.
It is advisable to store these in your own plugin because fetching them for each request would be a waste of resources.
Use the webhook listener and settings listener to update the stored value when necessary.

Below is a succinct example of how to get everything associated with a specific apikey:

```php
$apiKey                      = 'api_key_from_backoffice';
$accountService              = (new AccountWebService())->setApiKey($apiKey);
$carrierOptionsService       = (new CarrierOptionsWebService())->setApiKey($apiKey);
$carrierConfigurationService = (new CarrierConfigurationWebService())->setApiKey($apiKey);

$account        = $accountService->getAccount();
$shop           = $account->getShops()->first();
$shopId         = $shop->getId();
$carrierOptions = $carrierOptionsService->getCarrierOptions($shopId);
$configurations = [];

foreach ($carrierOptions as $carrierOption) {
    if (! $carrierOption->isEnabled()) {
        continue;
    }
    $configuration    = $carrierConfigurationService->getCarrierConfigurations(
        $shopId,
        $carrierOption->getCarrier()::getId()
    );
    $configurations[] = $configuration;
}

$accountSettings                           = [];
$accountSettings['shop']                   = $shop;
$accountSettings['account']                = $account;
$accountSettings['carrier_options']        = $carrierOptions;
$accountSettings['carrier_configurations'] = new Collection($configurations);

return new Collection($accountSettings);
```

### Filling in required drop off point for RedJePakketje

RedJePakketje requires a drop off point be added to each consignment.

The user may have indicated a default drop off point in the backoffice. If so, you will find it in their configuration settings.
These are accessible through the [account settings](#account-settings).
Access the configurations and then select the carrier. For an example see our WooCommerce plugin, file AccountSettings.php.

You can also get available drop off points from the webservice. It will return an indexed array holding data from drop off points near the supplied postalcode.
```php
$dropOffPoints = (new RedJePakketjeDropOffPointWebService())
      ->setApiKey('api_key_from_backoffice')
      ->getDropOffPoints('valid_dutch_postal_code');
```
You can use these to select a preferred drop off point. The `location_code` field provides a unique identifier.

Please note:
- The list for a given postalcode may be empty when there are no drop off points available in the vicinity.

Create a DropOffPoint using the relevant fields returned for the selected drop off point.
```php
$dropOffPoint = (new DropOffPoint())
    ->setBoxNumber()                            // unused for dutch addresses
    ->setCc('NL')                               // country code (field: cc) returned for the drop off point
    ->setCity('Amsterdam')                      // city (field: city)
    ->setLocationCode('location_code')          // field: location_code holds a unique id for the drop off point
    ->setLocationName('location_name')          // name of the drop off point (field: location_name)
    ->setStreet('Plein 1945')                   // street (field: street)
    ->setNumber(55)                             // number (field: number)
    ->setNumberSuffix('b')                      // number suffix (field: number_suffix)
    ->setPostalCode('2231JE')                   // postal code (field: postal_code)
    ->setRegion()                               // unused for dutch addresses
    ->setRetailNetworkId('retail_network_id')   // maybe used later
    ->setState();                               // unused for dutch addresses
```

If you have a `location_code`, you can get the drop off point like so:

```php
$dropOffPoint = (new RedJePakketjeDropOffPointWebService())
      ->setApiKey('api_key_from_backoffice')
      ->getDropOffPoint('location_code');
```

When you have the drop off point, set it on the consignment, which should be an otherwise valid RedJePakketje consignment.
```php
$consignment->setDropOffPoint($dropOffPoint);
```
The consignment can now be created, refer to [create a consignment](#create-a-consignment).

## List of classes and their methods
This is a list of all the classes in this SDK and their available methods.

### Models
`MyParcelNL/Sdk/src/Model`

#### PostNLConsignment
```MyparcelNL/Sdk/src/Model/Consignment/PostNLConsignment.php```
```php
$consignment = (new \MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment())
    ->setApiKey('api_key_from_backoffice')
    ->setReferenceIdentifier('order-146') // Not visible on the label
    
    // Recipient/address: https://myparcelnl.github.io/api/#7_B
    ->setPerson('Piet Hier')    // Name
    ->setEmail('test@test.nl')  // E-mail address
    ->setPhone('+31 612345678') // Phone number
    ->setCompany('Piet BV')     // Company
    
    ->setFullStreet('Plein 1945 55b') // Street, number and suffix in one line
    // OR send the street data separately:
    ->setStreet('Plein 1945') // Street
    ->setNumber(55)   // Number
    ->setNumberSuffix('b')    // Suffix
    
    ->setCity('Amsterdam')    // City
    ->setPostalCode('2231JE') // Postal code
    ->setCountry('NL')        // Country                
            
    // Available package types:
    // 1: Package (default)
    // 2: Mailbox package
    // 3: Letter
    // 4: Digital stamp
    ->setPackageType(1)

    // Options (https://myparcelnl.github.io/api/#6_A_3)
    ->setOnlyRecipient(false)   // Deliver the package only at address of the intended recipient. This option is required for Morning and Evening delivery types.
    ->setSignature(true)        // Recipient must sign for the package. This option is required for Pickup delivery type. 
    ->setReturn(true)           // Return the package to the sender when the recipient is not home.
    ->setLargeFormat(false)     // Must be specified if the dimensions of the package are between 100x70x50 and 175x78x58 cm. 
    ->setInsurance(250)         // Allows a shipment to be insured up to certain amount. Only packages (package type 1) can be insured. 
    
    ->setLabelDescription('Order 146') // This description will appear on the shipment label (for package type 1)
        
    // Delivery: https://myparcelnl.github.io/api/#8
    ->setDeliveryType()
    ->setDeliveryDate()
    ->setDeliveryRemark()    
    
    // Set pickup location
    ->setPickupLocationName('Supermarkt')
    ->setPickupStreet('Straatnaam')
    ->setPickupNumber('32')
    ->setPickupPostalCode('1234 AB')
    ->setPickupCity('Hoofddorp')
      
    // Physical properties
    ->setPhysicalProperties(['weight' => 73]) // Array with physical properties of the shipment. Currently only used to set the weight in grams for digital stamps (which is required)
    
    // Auto detect pickup
    ->setAutoDetectPickup(true) // When this setting is false MyParcel do not auto detect a PostNL pickup addresses.
    
    // Save recipient address
    ->setSaveRecipientAddress(true) // When this setting is true the recipient address will be saved in the address book.

    // Non-EU shipment attributes: see https://myparcelnl.github.io/api/#7_E
    ->setInvoice()
    ->setContents()
    ->addItem();

// Get attributes from consignment
$consignment
    ->getApiKey()
    ->getReferenceIdentifier()
    ->getBarcode() // Barcode is available after using setLinkOfLabels() or setPdfOfLabels() on the MyParcelCollection the consignment has been added to
    
    ->getLabelDescription()
    ->getConsignmentId()
    ->getShopId()
    ->getStatus()
    
    // Recipient info
    ->getPerson()
    ->getEmail()    
    ->getPhone()
    ->getCompany()

    // It doesn't matter whether you used setFullStreet() or set all parts separately
    ->getStreet()
    ->getStreetAdditionalInfo()
    ->getNumber()
    ->getNumberSuffix()
    ->getFullStreet()
    ->getPostalCode()
    ->getCity()
    ->getCountry()
    ->isCdCountry()
    ->isCorrectAddress()
    ->isEuCountry()
        
    // Package type
    ->getPackageType()
    
    // Get value of options
    ->isOnlyRecipient()
    ->isSignature()
    ->isReturn()
    ->isLargeFormat()
    ->getInsurance()
        
    // Get pickup location info
    ->getPickupLocationName()
    ->getPickupStreet()
    ->getPickupNumber()
    ->getPickupPostalCode()
    ->getPickupCity()
    
    // Delivery
    ->getDeliveryDate()
    ->getDeliveryType()
    
    // Physical properties (digital stamps or non-EU shipments)
    ->getPhysicalProperties()

    // Non-EU attributes
    ->getInvoice()
    ->getContents()
    ->getItems()
    ->getTotalWeight()
```

#### MyParcelCustomsItem
This object is embedded in the PostNLConsignment object for global shipments and is mandatory for non-EU shipments.

```MyParcelNL/Sdk/src/Model/MyParcelCustomsItem.php```
```php
  ->setAmount(3) // Amount of items in the package
  
  // ISIC/IDEP code (https://www.cbs.nl/nl-nl/deelnemers-enquetes/deelnemers-enquetes/bedrijven/onderzoek/lopend/internationale-handel-in-goederen/idep-codelijsten) 
  ->setClassification(0111) // Example: 0111 = "Growing of cereals (except rice), leguminous crops and oil seeds"  
  ->setCountry('NL') // Country of origin
  ->setDescription('Cereal grains')
  ->setItemValue(40000) // Price of item in cents
  ->setWeight() // The total weight for these items in whole grams. Between 0 and 20000 grams.
  
  ->getAmount()
  ->getClassification()
  ->getCountry()
  ->getDescription()
  ->getItemValue()
  ->getWeight()
  
  ->isFullyFilledItem()
```

### Helpers
```MyParcelNL/Sdk/src/Helper```

#### MyParcelCollection
Stores all consignments to communicate with the MyParcel API.
MyParcelCollection also contains almost [all methods](https://laravel.com/docs/5.7/collections) from Laravel Collections. If you use Laravel it also extends \Illuminate\Support\Collection.

```MyParcelNL/Sdk/src/Helper/MyParcelCollection.php```
```php
    ->addConsignment() // Add consignment to collection
    ->generateReturnConsignments() // Generate the return consignments based on already added consignments

    // Get consignments from the collection
    ->getConsignments()
    ->getConsignmentByApiId()
    ->getConsignmentsByReferenceIdentifier()

    // Clear the collection
    ->clearConsignmentsCollection()

    // Create or delete concept shipments in the MyParcel Backoffice
    ->createConcepts()
    ->deleteConcepts()
    
    ->getOneConsignment()
    ->getRequestBody()
    
    ->sendReturnLabelMails() // Send return label to customer. The customer can pay and download the label
    ->setLatestData() // Set id and run this function to update all the information about this shipment
    
    ->setLinkOfLabels()
    ->getLinkOfLabels()

    // Refer to 
    ->setPdfOfLabels()
    ->downloadPdfOfLabels()
    
    // To give us insight into which CMS system you're connecting from, you should send a User-Agent. 
    // If you're using a known CMS system it's required. 
    // You must send the name of the CMS system (required) followed by a version number (optional).
    ->setUserAgents(['name_of_cms', '1.0'])
    ->getUserAgent()
```

### Delivery options from the checkout with adapters

You can use DeliveryOptionsAdapterFactory if you use the following code in your checkout: https://github.com/myparcelnl/checkout
You can use these adapters to receive the options anywhere in your code in a consistent way. If you also have the options in a different format (for example $order['signature']), you can also make your own adapter.

```
try {
	// Create new instance from known json
	$deliveryOptions = MyParcelNL\Sdk\src\Factory\DeliveryOptionsAdapterFactory::create(json_decode($dataFromCheckout));
} catch (BadMethodCallException $e) {
	// Create new instance from your own data
	$deliveryOptions = new DeliveryOptionsFromOrderAdapter(null, (array) $meta);
}
```
Adapters are independent of consignments. It is therefore your responsibility to transform an adapter into a consignment.


## Exceptions

MyParcel uses several types of Exceptions to make the errors clear. It is your responsibility to provide the correct status in a response.
These are the Exceptions that we currently use:

#### InvalidConsignmentException
Exception to be returned when an address is incorrect or not usable.

Class: `MyParcelNL\Sdk\src\Exception\InvalidConsignmentException`

HTTP status: 412

#### ApiException
Exception to be returned when a call to MyParcel services has failed.

Class: `MyParcelNL\Sdk\src\Exception\ApiException`

HTTP status: 502

#### MissingFieldException
Exception thrown when there is an attempt to dynamically access a field that does not exist.

Class: `MyParcelNL\Sdk\src\Exception\MissingFieldException`

HTTP status: 500

#### InvalidArgumentException
Exception thrown if an argument is not the expected type.

Class: `\InvalidArgumentException`

HTTP status: 500

#### BadMethodCallException
Exception thrown if a callback refers to an undefined method or if some arguments are missing.

Class: `\BadMethodCallException:`

HTTP status: 500

## Tips
This SDK is not only useful for communicating with MyParcel. This package also contains code that you can take advantage of yourself:

### Collections
If you use arrays a lot, Collections are usually better to work with. ([documentation](https://laravel.com/docs/5.7/collections))
\MyParcelNL\Sdk\src\Support\Collection()

### Other helpers
* `\MyParcelNL\Sdk\src\Support\Arr` ([documentation](https://laravel.com/docs/5.7/helpers#arrays))
* `\MyParcelNL\Sdk\src\Support\Str` ([documentation](https://laravel.com/docs/5.7/helpers#method-camel-case))
* `\MyParcelNL\Sdk\src\Helper\SplitStreet::splitStreet('Plein 1940-45 3b'))`

## Contribute

1. Check for open issues or open a new issue to start a discussion around a bug or feature.
1. Fork the repository on GitHub to start making your changes.
1. Write one or more tests for the new feature or that expose the bug.
1. Make code changes to implement the feature or fix the bug.
1. Send a pull request to get your changes merged and published.
