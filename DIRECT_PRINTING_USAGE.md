# Direct Printing Usage

This guide shows how to use the direct printing functionality in the MyParcel PHP SDK.

## Overview

Direct printing allows you to send shipment labels directly to a printer without having to download PDFs first. This requires:
1. A printer group ID (obtainable via the API)
2. Consignments that have already been created in MyParcel

## Step 1: Get Printer Groups

Before you can print, you first need to retrieve the available printer groups:

```php
use MyParcelNL\Sdk\Services\Web\PrinterGroupWebService;

// Create a service instance
$printerGroupService = new PrinterGroupWebService();
$printerGroupService->setApiKey('your-api-key');

// Get all printer groups
$printerGroups = $printerGroupService->getPrinterGroups();

// Display available printer groups
foreach ($printerGroups as $group) {
    echo sprintf(
        "ID: %s - Name: %s\n",
        $group->getId(),
        $group->getName()
    );
}

// Select a printer group (e.g., the first one)
$selectedPrinterGroupId = $printerGroups->first()->getId();
```

## Step 2: Create Consignments

You need to create consignments first before you can print them:

```php
use MyParcelNL\Sdk\Helper\MyParcelCollection;
use MyParcelNL\Sdk\Model\Consignment\PostNLConsignment;
use MyParcelNL\Sdk\Model\Recipient;

// Create a new collection
$collection = new MyParcelCollection();

// Create a consignment
$consignment = (new PostNLConsignment())
    ->setApiKey('your-api-key')
    ->setReferenceIdentifier('ORDER-12345')
    ->setRecipient(
        (new Recipient())
            ->setPerson('John Doe')
            ->setStreet('Main Street')
            ->setNumber('123')
            ->setPostalCode('1234AB')
            ->setCity('Amsterdam')
            ->setCountry('NL')
            ->setEmail('john@example.com')
            ->setPhone('0612345678')
    )
    ->setWeight(500); // in grams

// Add the consignment to the collection
$collection->addConsignment($consignment);

// Optional: add more consignments
$consignment2 = (new PostNLConsignment())
    ->setApiKey('your-api-key')
    ->setReferenceIdentifier('ORDER-12346')
    ->setRecipient(/* ... */)
    ->setWeight(750);

$collection->addConsignment($consignment2);
```

## Step 3: Print Directly to Printer

Now you can send the labels directly to the printer:

```php
try {
    // Print all consignments in the collection directly to the selected printer group
    $result = $collection->printDirect($selectedPrinterGroupId);
    
    // The results contain information per API key
    foreach ($result as $apiKey => $printResult) {
        echo sprintf(
            "Print job ID: %s\nStatus: %s\nShipment IDs: %s\n",
            $printResult['data']['print_job_id'] ?? 'N/A',
            $printResult['data']['status'] ?? 'N/A',
            implode(', ', $printResult['data']['shipment_ids'] ?? [])
        );
    }
    
    echo "Labels have been sent to the printer!\n";
    
} catch (\MyParcelNL\Sdk\Exception\ApiException $e) {
    echo "Error while printing: " . $e->getMessage() . "\n";
} catch (\MyParcelNL\Sdk\Exception\MissingFieldException $e) {
    echo "Missing field: " . $e->getMessage() . "\n";
} catch (\MyParcelNL\Sdk\Exception\AccountNotActiveException $e) {
    echo "Account not active: " . $e->getMessage() . "\n";
}
```

## Complete Example

Here is a complete working example:

```php
<?php

require_once 'vendor/autoload.php';

use MyParcelNL\Sdk\Helper\MyParcelCollection;
use MyParcelNL\Sdk\Model\Consignment\PostNLConsignment;
use MyParcelNL\Sdk\Model\Recipient;
use MyParcelNL\Sdk\Services\Web\PrinterGroupWebService;

$apiKey = 'your-api-key';

// Step 1: Get printer groups
$printerGroupService = new PrinterGroupWebService();
$printerGroupService->setApiKey($apiKey);

$printerGroups = $printerGroupService->getPrinterGroups();

if ($printerGroups->isEmpty()) {
    die("No printer groups found. Make sure you have set up printer groups in your MyParcel account.\n");
}

// Select the first printer group (or use a specific ID)
$printerGroupId = $printerGroups->first()->getId();
echo "Using printer group: {$printerGroups->first()->getName()} (ID: {$printerGroupId})\n\n";

// Step 2: Create consignments
$collection = new MyParcelCollection();

$consignment = (new PostNLConsignment())
    ->setApiKey($apiKey)
    ->setReferenceIdentifier('ORDER-' . time())
    ->setRecipient(
        (new Recipient())
            ->setPerson('John Doe')
            ->setStreet('Main Street')
            ->setNumber('123')
            ->setPostalCode('1234AB')
            ->setCity('Amsterdam')
            ->setCountry('NL')
            ->setEmail('john@example.com')
            ->setPhone('0612345678')
    )
    ->setWeight(500);

$collection->addConsignment($consignment);

// Step 3: Print directly
try {
    $result = $collection->printDirect($printerGroupId);
    
    echo "Print job successfully sent!\n";
    foreach ($result as $apiKey => $printResult) {
        if (isset($printResult['data'])) {
            echo "Status: {$printResult['data']['status']}\n";
            echo "Print Job ID: {$printResult['data']['print_job_id']}\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

## Important Notes

1. **Consignments must be created first**: The `printDirect()` method automatically calls `createConcepts()` if the consignments haven't been created yet.

2. **Printer Groups**: You need to set up printer groups in your MyParcel account first before you can use direct printing. This can be done via the MyParcel web interface.

3. **Multiple API keys**: If you have consignments with different API keys, they will be grouped and sent to the printer separately.

4. **Response structure**: The `printDirect()` method returns an array with results per API key. Each entry contains the print job information.

5. **Error handling**: Make sure to handle exceptions, especially `ApiException`, `MissingFieldException`, and `AccountNotActiveException`.

## Alternative: Print Existing Consignments

If you already have consignments (e.g., via a query), you can also print them directly:

```php
use MyParcelNL\Sdk\Helper\MyParcelCollection;

// Get existing consignments
$collection = MyParcelCollection::query($apiKey, [
    'size' => 10,
    'status' => 1, // Concept status
]);

// Print them directly
$printerGroupId = 'your-printer-group-id';
$result = $collection->printDirect($printerGroupId);
```

## Difference with Traditional Printing

**Traditional (PDF download):**
```php
$collection->setPdfOfLabels();
$collection->downloadPdfOfLabels();
```

**Direct printing:**
```php
$collection->printDirect($printerGroupId);
```

Direct printing is more convenient because:
- You don't need to download PDFs
- Labels go directly to the printer
- Fewer steps are required
- No browser interaction is needed
