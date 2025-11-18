<?php

/**
 * Direct Printing Example
 * 
 * This example demonstrates how to use the direct printing functionality
 * in the MyParcel PHP SDK.
 * 
 * Usage:
 *   php examples/direct-printing-example.php
 * 
 * Make sure to set your API key in the $apiKey variable below.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use MyParcelNL\Sdk\Helper\MyParcelCollection;
use MyParcelNL\Sdk\Model\Consignment\PostNLConsignment;
use MyParcelNL\Sdk\Services\Web\PrinterGroupWebService;
use MyParcelNL\Sdk\Exception\ApiException;
use MyParcelNL\Sdk\Exception\MissingFieldException;
use MyParcelNL\Sdk\Exception\AccountNotActiveException;

// ============================================
// CONFIGURATION
// ============================================
$apiKey = '';

if ($apiKey === 'your-api-key-here') {
    echo "Please set your API key!\n";
    echo "   Option 1: Set environment variable: export MYPARCEL_API_KEY=your-key\n";
    echo "   Option 2: Edit this file and set \$apiKey directly\n\n";
    exit(1);
}

// ============================================
// STEP 1: Get Printer Groups
// ============================================
echo "Step 1: Getting printer groups...\n";

$printerGroupId = null;
$printerGroupName = null;

try {
    $printerGroupService = new PrinterGroupWebService();
    $printerGroupService->setApiKey($apiKey);
    
    $printerGroups = $printerGroupService->getPrinterGroups();
    
    if ($printerGroups->isEmpty()) {
        echo "No printer groups found via API.\n";
        echo "   You can manually enter a printer group ID below.\n";
        echo "   Printer groups can be found in your MyParcel account settings.\n\n";
    } else {
        echo "Found " . $printerGroups->count() . " printer group(s):\n";
        foreach ($printerGroups as $index => $group) {
            echo "   " . ($index + 1) . ". {$group->getName()} (ID: {$group->getId()})\n";
        }
        
        // Select the first printer group
        $printerGroupId = $printerGroups->first()->getId();
        $printerGroupName = $printerGroups->first()->getName();
        echo "\nUsing printer group: {$printerGroupName} (ID: {$printerGroupId})\n\n";
    }
    
} catch (AccountNotActiveException $e) {
    echo "Could not fetch printer groups: " . $e->getMessage() . "\n";
    echo "   You can manually enter a printer group ID below.\n\n";
} catch (ApiException $e) {
    echo "Could not fetch printer groups: " . $e->getMessage() . "\n";
    echo "   The printer_groups endpoint might not be available yet.\n";
    echo "   You can manually enter a printer group ID below.\n\n";
} catch (MissingFieldException $e) {
    echo "Missing field: " . $e->getMessage() . "\n";
    echo "   You can manually enter a printer group ID below.\n\n";
}

// If we don't have a printer group ID, ask for manual input
if (!$printerGroupId) {
    echo "To use direct printing, you need a printer group ID.\n";
    echo "   You can find this in your MyParcel account under printer settings.\n";
    echo "   Or you can skip this step and use the printer group ID directly in your code.\n\n";
    
    // For demo purposes, we'll exit here
    // In a real application, you could prompt for input or use a config value
    echo "Skipping direct print demo. To test direct printing:\n";
    echo "   1. Get your printer group ID from MyParcel account\n";
    echo "   2. Set it in the script: \$printerGroupId = 'your-printer-group-id';\n";
    echo "   3. Uncomment the print step below\n\n";
    
    // Uncomment the line below and set your printer group ID to test
    //     $printerGroupId = '';
    
    if (!$printerGroupId) {
        echo "Step 2: Creating consignments (for testing)...\n";
        // Continue with creating consignments to show it works
    }
}

// ============================================
// STEP 2: Create Consignments
// ============================================
echo "Step 2: Creating consignments...\n";

$collection = new MyParcelCollection();

// Create a test consignment
$consignment = (new PostNLConsignment())
    ->setApiKey($apiKey)
    ->setReferenceIdentifier('DEMO-ORDER-' . time())
    ->setPerson('John Doe')
    ->setStreet('Main Street')
    ->setNumber('123')
    ->setCountry('NL')
    ->setPostalCode('1234AB')
    ->setCity('Amsterdam')
    ->setEmail('john@example.com')
    ->setPhone('0612345678')
    ->setTotalWeight(500); // 500 grams

$collection->addConsignment($consignment);

echo "Created 1 consignment with reference: {$consignment->getReferenceIdentifier()}\n\n";

// ============================================
// STEP 3: Print Directly
// ============================================
if (!$printerGroupId) {
    echo "⏭Step 3: Skipping direct print (no printer group ID available)\n";
    echo "   To test direct printing, set a printer group ID above.\n\n";
} else {
    echo "Step 3: Printing directly to printer...\n";

    try {
        $result = $collection->printDirect($printerGroupId);
        
        echo "Print job successfully sent!\n\n";
        
        foreach ($result as $apiKey => $printResult) {
            if (isset($printResult['data'])) {
                $data = $printResult['data'];
                
                if (isset($data['ids'])) {
                    // Response structure: shipments were created/updated and sent to printer
                    $ids = array_map(function($item) {
                        return is_array($item) ? ($item['id'] ?? 'unknown') : $item;
                    }, $data['ids']);
                    echo "Shipments created and sent to printer:\n";
                    echo "   Shipment IDs: " . implode(', ', $ids) . "\n";
                } elseif (isset($data['status']) || isset($data['print_job_id'])) {
                    // Print job response structure
                    echo "Print Job Details:\n";
                    echo "   Status: " . ($data['status'] ?? 'N/A') . "\n";
                    echo "   Print Job ID: " . ($data['print_job_id'] ?? 'N/A') . "\n";
                    if (isset($data['shipment_ids']) && is_array($data['shipment_ids'])) {
                        echo "   Shipment IDs: " . implode(', ', $data['shipment_ids']) . "\n";
                    }
                }
            }
        }
        
        echo "\n Labels have been sent to printer group: {$printerGroupName}\n";
        echo "   Check your printer to see the labels!\n\n";
        
    } catch (AccountNotActiveException $e) {
        echo "Account not active: " . $e->getMessage() . "\n";
        echo "\nCommon issues:\n";
        echo "   - Make sure your printer group is properly configured\n";
        echo "   - Check that your account has direct printing enabled\n";
        echo "   - Verify your API key has the correct permissions\n";
        exit(1);
    } catch (ApiException $e) {
        echo "API Error:\n";
        echo $e->getMessage() . "\n";
        
        echo "\n💡 Common issues:\n";
        echo "   - Make sure your printer group is properly configured\n";
        echo "   - Check that your account has direct printing enabled\n";
        echo "   - Verify your API key has the correct permissions\n";
        echo "   - Ensure the shipment IDs are valid and the consignments exist\n";
        echo "   - Check the full error response above for more details\n";
        exit(1);
    } catch (MissingFieldException $e) {
        echo "Missing field: " . $e->getMessage() . "\n";
        exit(1);
    } catch (\Exception $e) {
        echo "Unexpected error: " . $e->getMessage() . "\n";
        echo "   Type: " . get_class($e) . "\n";
        exit(1);
    }
}

echo "Done!\n";

