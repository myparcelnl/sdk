<?php declare(strict_types=1);
/**
 * Stores all data to communicate with the MyParcel API
 *
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2020 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v0.1.0
 */

namespace MyParcelNL\Sdk\Helper;

use BadMethodCallException;
use Closure;
use InvalidArgumentException;
use MyParcelNL\Sdk\Adapter\ConsignmentAdapter;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Exception\AccountNotActiveException;
use MyParcelNL\Sdk\Exception\ApiException;
use MyParcelNL\Sdk\Exception\MissingFieldException;
use MyParcelNL\Sdk\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\Model\Carrier\CarrierUPSStandard;
use MyParcelNL\Sdk\Model\Carrier\CarrierUPSExpressSaver;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Model\Consignment\BaseConsignment;
use MyParcelNL\Sdk\Model\MyParcelRequest;
use MyParcelNL\Sdk\Model\RequestBody;
use MyParcelNL\Sdk\Services\CollectionEncode;
use MyParcelNL\Sdk\Services\ConsignmentEncode;
use MyParcelNL\Sdk\Support\Arr;
use MyParcelNL\Sdk\Support\Collection;
use MyParcelNL\Sdk\Support\Str;

/**
 * Stores all data to communicate with the MyParcel API
 *
 * @property \MyParcelNL\Sdk\Model\Consignment\AbstractConsignment[] $items
 */
class MyParcelCollection extends Collection
{
    use HasUserAgent;

    private const PREFIX_PDF_FILENAME = 'myparcel-label-';
    private const DEFAULT_A4_POSITION = 1;

    /**
     * @var string
     */
    private $paper_size = 'A6';

    /**
     * The position of the label on the paper.
     * pattern: [1 - 4]
     * example: 1. (top-left)
     *          2. (top-right)
     *          3. (bottom-left)
     *          4. (bottom-right)
     *
     * @var string
     */
    private $label_position;

    /**
     * Link to download the PDF
     *
     * @var string
     */
    private $label_link;

    /**
     * Label in PDF format
     *
     * @var string
     */
    private $label_pdf;

    /**
     * @param bool $keepKeys
     *
     * @return AbstractConsignment[]
     */
    public function getConsignments($keepKeys = true): array
    {
        if ($keepKeys) {
            return $this->items;
        }

        return array_values($this->items);
    }

    /**
     * Get one consignment
     *
     * @return \MyParcelNL\Sdk\Model\Consignment\AbstractConsignment
     */
    public function getOneConsignment()
    {
        if ($this->count() > 1) {
            throw new BadMethodCallException('Can\'t run getOneConsignment(): Multiple items found');
        }

        return $this->first();
    }

    /**
     * @param string|null $id
     *
     * @return self
     */
    public function getConsignmentsByReferenceId($id): self
    {
        if ($id === null) {
            throw new InvalidArgumentException('Can\'t run getConsignmentsByReferenceId() because referenceId can\'t be null');
        }

        return $this->where('reference_identifier', $id);
    }

    /**
     * @param $groupId
     *
     * @return self
     */
    public function getConsignmentsByReferenceIdGroup($groupId): self
    {
        return $this->findByReferenceIdGroup($groupId);
    }

    /**
     * @param int $id
     *
     * @return AbstractConsignment
     */
    public function getConsignmentByApiId($id)
    {
        return $this->where('consignment_id', $id)->first();
    }

    /**
     * @return string
     *
     * this is used by third parties to access the label_pdf variable.
     */
    public function getLabelPdf()
    {
        return $this->label_pdf;
    }

    /**
     * @return string
     */
    public function getLinkOfLabels()
    {
        return $this->label_link;
    }

    /**
     * @param AbstractConsignment $consignment
     *
     * @return self
     * @throws MissingFieldException
     */
    public function addConsignment(AbstractConsignment $consignment): self
    {
        if ($consignment->getApiKey() === null) {
            throw new MissingFieldException('First set the API key with setApiKey() before running addConsignment()');
        }
        $consignment->validate();
        $this->push($consignment);

        return $this;
    }

    /**
     * @param AbstractConsignment $consignment
     * @param int $amount
     *
     * @return self
     * @throws MissingFieldException
     */
    public function addMultiCollo(AbstractConsignment $consignment, int $amount): self
    {
        if ($amount <= 1) {
            $this->addConsignment($consignment);
            return $this;
        }

        // Create multiple consignments with equally distributed weight
        $originalWeight = $consignment->getTotalWeight();
        $weightPerCollo = $originalWeight / $amount;
        
        $consignments = [];
        for ($i = 1; $i <= $amount; $i++) {
            $clonedConsignment = clone $consignment;
            $clonedConsignment->setTotalWeight($weightPerCollo);
            $consignments[] = $clonedConsignment;
        }

        return $this->addMultiColloConsignments($consignments);
    }

    /**
     * @param AbstractConsignment[] $consignments
     *
     * @return self
     * @throws MissingFieldException
     * @throws \Exception
     */
    public function addMultiColloConsignments(array $consignments): self
    {
        if (empty($consignments)) {
            return $this;
        }

        // Check if all consignments have the same carrier
        $carrierIds = array_map(static function($consignment) {
            return $consignment->getCarrierId();
        }, $consignments);
        if (count(array_unique($carrierIds)) > 1) {
            throw new \Exception('All consignments in a multi collo shipment must have the same carrier.');
        }

        // Set multi collo and reference identifier for all consignments
        $referenceId = $consignments[0]->getReferenceIdentifier() ?? ('multi_collo_' . uniqid('', true));
        foreach ($consignments as $consignment) {
            $consignment->setMultiCollo(true);
            $consignment->setReferenceIdentifier($referenceId);
            $this->addConsignment($consignment);
        }

        return $this;
    }

    /**
     * @param int[]  $ids
     * @param string $apiKey
     *
     * @return self
     * @throws MissingFieldException
     */
    public function addConsignmentByConsignmentIds(array $ids, string $apiKey): self
    {
        foreach ($ids as $consignmentId) {
            $consignment = (new BaseConsignment())
                ->setApiKey($apiKey)
                ->setConsignmentId($consignmentId);

            $this->addConsignment($consignment);
        }

        return $this;
    }

    /**
     * @param string[] $ids
     * @param string   $apiKey
     *
     * @return self
     * @throws MissingFieldException
     */
    public function addConsignmentByReferenceIds($ids, $apiKey): self
    {
        foreach ($ids as $referenceId) {
            $consignment = (new BaseConsignment())
                ->setApiKey($apiKey)
                ->setReferenceIdentifier($referenceId);

            $this->addConsignment($consignment);
        }

        return $this;
    }

    /**
     * Create concepts in MyParcel.
     *
     * @return self
     * @throws AccountNotActiveException
     * @throws ApiException
     * @throws MissingFieldException
     */
    public function createConcepts(): self
    {
        return $this->createConsignments();
    }

    /**
     * Create concept consignments in MyParcel.
     *
     * @param bool $asUnrelatedReturn default false will create normal consignments, supply true for unrelated returns
     * @param string|null $printerGroupId if provided, will send shipments directly to printer instead of returning PDF
     * @return self
     * @throws AccountNotActiveException
     * @throws ApiException
     * @throws MissingFieldException
     */
    protected function createConsignments(bool $asUnrelatedReturn = false, ?string $printerGroupId = null): self
    {
        $newConsignments = $this->where('consignment_id', '!=', null)->toArray();
        $this->addMissingReferenceId();

        $grouped = $this->where('consignment_id', null)->groupBy(function(AbstractConsignment $item) {
            return $item->getApiKey() . ($item->hasSender() ? '-sender' : '');
        });

        /* @var MyParcelCollection $consignments */
        foreach ($grouped as $consignments) {
            $headers = $asUnrelatedReturn ? MyParcelRequest::HEADER_CONTENT_TYPE_UNRELATED_RETURN_SHIPMENT : MyParcelRequest::HEADER_CONTENT_TYPE_SHIPMENT;
            if ($consignments->first()->hasSender()) {
                $headers += MyParcelRequest::HEADER_SET_CUSTOM_SENDER;
            }

            // Add direct print header if printer group ID is provided
            if (null !== $printerGroupId) {
                $directPrintHeader = MyParcelRequest::getDirectPrintAcceptHeader($printerGroupId);
                $headers['Accept'] = $directPrintHeader['Accept'];
            }

            $data    = (new CollectionEncode($consignments))->encode($asUnrelatedReturn ? 'return_shipments' : 'shipments');
            $request = (new MyParcelRequest())
                ->setUserAgents($this->getUserAgent())
                ->setRequestParameters(
                    $consignments->first()->getApiKey(),
                    $data,
                    $headers
                )
                ->sendRequest();



            /**
             * Loop through the returned ids and add each consignment id to a consignment.
             */
            foreach ($request->getResult('data.ids') as $responseShipment) {
                $consignments      = $this->getConsignmentsByReferenceId($responseShipment['reference_identifier']);
                $consignment       = clone $consignments->pop();
                $newConsignments[] = $consignment->setConsignmentId($responseShipment['id']);
            }
        }

        $this->items = $newConsignments;

        return $this;
    }

    /**
     * @throws ApiException
     * @throws AccountNotActiveException
     * @throws MissingFieldException
     */
    public function createUnrelatedReturns(): self
    {
        return $this->createConsignments(true);
    }

    /**
     * Label prepare wil be active from x number of orders
     *
     * @param int $numberOfShipments
     *
     * @return bool
     */
    public function useLabelPrepare(int $numberOfShipments): bool
    {
        return $numberOfShipments > MyParcelRequest::SHIPMENT_LABEL_PREPARE_ACTIVE_FROM;
    }

    /**
     * Delete concepts in MyParcel
     *
     * @return  $this
     * @throws AccountNotActiveException
     * @throws ApiException
     * @throws MissingFieldException
     */
    public function deleteConcepts(): self
    {
        /* @var AbstractConsignment[] $consignments */
        foreach ($this->groupBy('api_key')->where('consignment_id', '!=', null) as $key => $consignments) {
            foreach ($consignments as $consignment) {
                (new MyParcelRequest())
                    ->setUserAgents($this->getUserAgent())
                    ->setRequestParameters(
                        $key,
                        (string) $consignment->getConsignmentId()
                    )
                    ->sendRequest('DELETE');
            }
        }

        return $this;
    }

    /**
     * Get all current data
     * Set id and run this function to update all the information about this shipment
     *
     * @param int $size
     *
     * @return self
     * @throws AccountNotActiveException
     * @throws ApiException
     * @throws MissingFieldException
     */
    public function setLatestData(int $size = 300): self
    {
        $collections          = [];
        $consignmentsByApiKey = $this->groupBy(function ($consignment) {
            return $consignment->getApiKey();
        });

        foreach ($consignmentsByApiKey as $key => $consignments) {
            $myParcelRequest = new MyParcelRequest();

            $request = $myParcelRequest
                ->setUserAgents($this->getUserAgent())
                ->setRequestParameters(
                    $key,
                    $myParcelRequest::getLatestDataParameters($consignments, $size),
                )
                ->sendRequest('GET');

            if (null === $request->getResult()) {
                throw new ApiException('Unknown Error in MyParcel API response');
            }

            $result        = $request->getResult('data.shipments');
            if (null === $result || !is_array($result)) {
                continue;
            }
            $newCollection = $this->getNewCollectionFromResult($result, $key);
            $collections[] = $newCollection->sortByCollection($this)->items;
        }

        $this->items = array_merge(...$collections);

        return $this;
    }

    /**
     * Get link of labels
     *
     * @param mixed $positions The position(s) of the label(s) on an A4 sheet or false for an A6 sheet.
     *                          Positioning is only applied on the first page with labels. All subsequent pages will use the default positioning `[1,2,3,4]`.
     *                          Pass an array to specify the positions on an A4 sheet, e.g. `[2,3,4]`.
     *                          Pass a number to specify the starting position on an A4 sheet, e.g. `2`. The following labels will fill the subsequent positions.
     *                          Pass a falsy value to use an A6 sheet, e.g. `false` or `null`.
     *
     * @return self
     * @throws AccountNotActiveException
     * @throws ApiException
     * @throws MissingFieldException
     */
    public function setLinkOfLabels($positions = self::DEFAULT_A4_POSITION): self
    {
        $urlLocation = 'pdfs';

        /** If $positions is not false, set paper size to A4 */
        $this
            ->createConcepts()
            ->setLabelFormat($positions);

        $conceptIds  = $this->getConsignmentIds($key);
        $requestType = MyParcelRequest::REQUEST_TYPE_RETRIEVE_LABEL;
        if ($this->useLabelPrepare(count($conceptIds))) {
            $requestType = MyParcelRequest::REQUEST_TYPE_RETRIEVE_PREPARED_LABEL;
            $urlLocation = 'pdf';
        }

        if ($key) {
            $request = (new MyParcelRequest())
                ->setUserAgents($this->getUserAgent())
                ->setRequestParameters(
                    $key,
                    implode(';', $conceptIds) . '/' . $this->getRequestBody()
                )
                ->sendRequest('GET', $requestType);

            $this->label_link = (new MyParcelRequest())->getRequestUrl() . $request->getResult("data.$urlLocation.url");
        }

        $this->setLatestData();

        return $this;
    }

    /**
     * Receive label PDF
     * After setPdfOfLabels() apiId and barcode is present
     *
     * @param mixed $positions The position(s) of the label(s) on an A4 sheet or false for an A6 sheet.
     *                          Positioning is only applied on the first page with labels. All subsequent pages will use the default positioning `[1,2,3,4]`.
     *                          Pass an array to specify the positions on an A4 sheet, e.g. `[2,3,4]`.
     *                          Pass a number to specify the starting position on an A4 sheet, e.g. `2`. The following labels will fill the subsequent positions.
     *                          Pass a falsy value to use an A6 sheet, e.g. `false` or `null`.
     *
     * @return self
     * @throws AccountNotActiveException
     * @throws ApiException
     * @throws MissingFieldException
     */
    public function setPdfOfLabels($positions = self::DEFAULT_A4_POSITION): self
    {
        $this
            ->createConcepts()
            ->setLabelFormat($positions);

        $PdfMerger = new FpdfMerge();

        $consignmentIdsByApiKey = $this->getConsignmentIdsByApiKey();

        foreach ($consignmentIdsByApiKey as $key => $consignmentIds) {
            $request = (new MyParcelRequest())
                ->setUserAgents($this->getUserAgent())
                ->setRequestParameters(
                    $key,
                    implode(';', $consignmentIds) . '/' . $this->getRequestBody(),
                    MyParcelRequest::HEADER_ACCEPT_APPLICATION_PDF
                )
                ->sendRequest('GET', MyParcelRequest::REQUEST_TYPE_RETRIEVE_LABEL);

            /**
             * When account needs to pay upfront, an array is returned with payment information,
             * instead of the actual pdf's. It will throw an unintelligible error when not handled here.
             */
            $result = $request->getResult();

            if (!is_string($result) || !preg_match('/^%PDF-\d/', $result)) {
                if (is_array($result) && isset($result['data']['payment_instructions'])) {
                    throw new ApiException('Received payment link instead of pdf. Check your MyParcel account status.');
                }
                throw new ApiException('Did not receive expected pdf response. Please contact MyParcel.');
            }

            // merge this pdf into the existing pdf
            $fileResource = fopen('php://memory', 'rb+');
            fwrite($fileResource, $result);
            $PdfMerger->add($fileResource);
        }

        $this->label_pdf = $PdfMerger->output('S');

        $this->setLatestData();

        return $this;
    }

    /**
     * Download labels
     *
     * @param bool $inline_download
     *
     * @return void
     * @throws MissingFieldException
     */
    public function downloadPdfOfLabels($inline_download = false): void
    {
        if (! $this->label_pdf) {
            throw new MissingFieldException(
                'First set label_pdf key with setPdfOfLabels() before running downloadPdfOfLabels()'
            );
        }

        header('Content-Type: application/pdf');
        header('Content-Length: ' . strlen($this->label_pdf));
        header('Content-disposition: ' . ($inline_download === true ? "inline" : "attachment") . '; filename="' . self::PREFIX_PDF_FILENAME . gmdate('Y-M-d H-i-s') . '.pdf"');
        header('Cache-Control: public, must-revalidate, max-age=0');
        header('Pragma: public');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        echo $this->label_pdf;
        exit;
    }

    /**
     * Print labels directly to a printer
     * 
     * This method uses createConsignments() with a printer group ID to send shipments
     * directly to a printer instead of returning a PDF. The API response structure is
     * the same as createConcepts(), with an additional 'pdf' field.
     *
     * @param string $printerGroupId The ID of the printer group to print to
     *
     * @return array The API response, grouped by API key
     * @throws AccountNotActiveException
     * @throws ApiException
     * @throws MissingFieldException
     */
    public function printDirect(string $printerGroupId): self
    {
        $this->createConsignments(false, $printerGroupId);
        $this->setLatestData();

        return $this;
    }

    /**
     * Send return label to customer. The customer can pay and download the label.
     *
     * @param bool          $sendMail
     * @param \Closure|null $modifier
     *
     * @return self
     * @throws AccountNotActiveException
     * @throws ApiException
     * @throws MissingFieldException
     */
    public function generateReturnConsignments(bool $sendMail, ?Closure $modifier = null): self
    {
        // Be sure consignments are created
        $this->createConcepts();

        $parentConsignments = $this->getConsignments(false);
        $returnConsignments = $this->getReturnConsignments($parentConsignments, $modifier);

        $data   = $this->apiEncodeReturnShipments($returnConsignments);
        $apiKey = $returnConsignments[0]->getApiKey();

        $request = (new MyParcelRequest())
            ->setUserAgents($this->getUserAgent())
            ->setRequestParameters(
                $apiKey,
                $data,
                MyParcelRequest::HEADER_CONTENT_TYPE_RETURN_SHIPMENT
            )
            ->setQuery(['send_return_mail' => (int) $sendMail])
            ->sendRequest('POST', MyParcelRequest::REQUEST_TYPE_SHIPMENTS);

        $result = $request->getResult();

        if ($result === null) {
            throw new ApiException('Unknown Error in MyParcel API response');
        }

        $returnIds = Arr::pluck(Arr::get($result, 'data.ids'), 'id');
        if (! $returnIds || count($returnIds) < 1) {
            throw new InvalidArgumentException('Can\'t send return label to customer. Please create an issue on GitHub or contact MyParcel; support@myparcel.nl. Note this request body: ' . $data);
        }

        $returnConsignments = (new MyParcelCollection())
            ->addConsignmentByConsignmentIds($returnIds, $apiKey)
            ->setLatestData();

        $this->items = Arr::mergeAfterEachOther($parentConsignments, $returnConsignments->toArray());

        return $this;
    }

    /**
     * Get all consignment ids
     *
     * @param string|null $key
     *
     * @return array|null
     * @deprecated use getConsignmentIdsByApiKey() to get the consignment ids grouped by their original api key
     */
    public function getConsignmentIds(?string &$key = null): ?array
    {
        $conceptIds = [];
        /** @var AbstractConsignment $consignment */
        foreach ($this->where('consignment_id', '!=', null) as $consignment) {
            $conceptIds[] = $consignment->getConsignmentId();
            $key          = $consignment->getApiKey();
        }

        if (empty($conceptIds)) {
            return null;
        }

        return $conceptIds;
    }

    public function getConsignmentIdsByApiKey(): ?array {
        $consignmentIds = [];

        /** @var AbstractConsignment $consignment */
        foreach ($this->where('consignment_id', '!=', null) as $consignment) {
            $consignmentIds[$consignment->getApiKey()][] = $consignment->getConsignmentId();
        }

        if (empty($consignmentIds)) {
            return null;
        }

        return $consignmentIds;
    }

    /**
     * @return string
     */
    public function getRequestBody(): string
    {
        return $this->paper_size === 'A4' ? '?format=A4&positions=' . $this->label_position : '?format=A6';
    }

    /**
     * Clear this collection
     */
    public function clearConsignmentsCollection(): void
    {
        $this->items = [];
    }

    /**
     * To search and filter consignments by certain values
     *
     * @param string $apiKey
     * @param mixed  $parameters May be an array or object containing properties.
     *                            If query_data is an array, it may be a simple one-dimensional structure,
     *                            or an array of arrays (which in turn may contain other arrays).
     *                            If query_data is an object, then only public properties will be incorporated
     *                            into the result.
     *
     * @return self
     * @throws AccountNotActiveException
     * @throws ApiException
     * @throws MissingFieldException
     */
    public static function query(string $apiKey, $parameters): self
    {
        $collection = new static();

        // The field `size` is required to prevent bugs. Think carefully about what
        // the maximum size should be in your use case. If you want to pick up all
        // open consignments for example, you would probably want to adjust size to 300.
        if (empty($parameters['size'])) {
            throw new MissingFieldException('Field "size" is required.');
        }

        $request = (new MyParcelRequest())
            ->setRequestParameters($apiKey)
            ->setQuery($parameters)
            ->sendRequest('GET');

        if ($request->getResult() === null) {
            throw new ApiException('Unknown error in MyParcel API response');
        }

        foreach ($request->getResult()['data']['shipments'] as $shipment) {
            $consignmentAdapter = new ConsignmentAdapter($shipment, (ConsignmentFactory::createByCarrierId($shipment['carrier_id'])->setApiKey($apiKey)));
            $collection->addConsignment($consignmentAdapter->getConsignment());
        }

        return $collection;
    }

    /**
     * @param int    $id
     * @param string $apiKey
     *
     * @return self
     * @throws AccountNotActiveException
     * @throws ApiException
     * @throws MissingFieldException
     */
    public static function find(int $id, string $apiKey): self
    {
        return self::findMany([$id], $apiKey);
    }

    /**
     * @param array  $consignmentIds
     * @param string $apiKey
     *
     * @return self
     * @throws AccountNotActiveException
     * @throws ApiException
     * @throws MissingFieldException
     */
    public static function findMany(array $consignmentIds, string $apiKey): self
    {
        $collection = new static();

        foreach ($consignmentIds as $id) {
            $consignment = new BaseConsignment();
            $consignment->setConsignmentId((int) $id);
            $consignment->setApiKey($apiKey);

            $collection->addConsignment($consignment);
        }

        $collection->setLatestData();

        return $collection;
    }

    /**
     * @param string $id
     * @param string $apiKey
     *
     * @return self
     * @throws AccountNotActiveException
     * @throws ApiException
     * @throws MissingFieldException
     */
    public static function findByReferenceId(string $id, string $apiKey): self
    {
        return self::findManyByReferenceId([$id], $apiKey);
    }

    /**
     * @param array  $referenceIds
     * @param string $apiKey
     *
     * @return self
     * @throws AccountNotActiveException
     * @throws ApiException
     * @throws MissingFieldException
     */
    public static function findManyByReferenceId(array $referenceIds, string $apiKey): self
    {
        $collection = new static();

        foreach ($referenceIds as $id) {
            $consignment = new BaseConsignment();
            $consignment->setReferenceIdentifier($id);
            $consignment->setApiKey($apiKey);

            $collection->addConsignment($consignment);
        }

        $collection->setLatestData();

        return $collection;
    }

    /**
     * @param \MyParcelNL\Sdk\Helper\MyParcelCollection|\MyParcelNL\Sdk\Model\Consignment\AbstractConsignment[] $sortedCollection
     *
     * @return self
     */
    public function sortByCollection(MyParcelCollection $sortedCollection): self
    {
        $result = new MyParcelCollection();

        foreach ($sortedCollection as $sorted) {
            $consignment = $this->where('consignment_id', $sorted->getConsignmentId())->first();

            if ($consignment) {
                $result[] = $consignment;
            }
        }

        $leftItems = $this->whereNotIn('consignment_id', $result->getConsignmentIds());

        return $result->merge($leftItems);
    }

    /**
     * Sets label format settings
     *
     * @param mixed $positions The position(s) of the label(s) on an A4 sheet or false for an A6 sheet.
     *                          Positioning is only applied on the first page with labels. All subsequent pages will use the default positioning `[1,2,3,4]`.
     *                          Pass an array to specify the positions on an A4 sheet, e.g. `[2,3,4]`.
     *                          Pass a number to specify the starting position on an A4 sheet, e.g. `2`. The following labels will fill the subsequent positions.
     *                          Pass a falsy value to use an A6 sheet, e.g. `false` or `null`.
     *
     * @return self
     */
    private function setLabelFormat($positions): self
    {
        /** If $positions is not falsy, set paper size to A4 */
        if (is_numeric($positions)) {
            /** Generating positions for A4 paper */
            $this->paper_size     = 'A4';
            $this->label_position = LabelHelper::getPositions($positions);
        } elseif (is_array($positions)) {
            /** Set positions for A4 paper */
            $this->paper_size     = 'A4';
            $this->label_position = implode(';', $positions);
        } else {
            /** Set paper size to A6 */
            $this->paper_size     = 'A6';
            $this->label_position = null;
        }

        return $this;
    }

    /**
     * Encode ReturnShipment to send to MyParcel
     *
     * @param \MyParcelNL\Sdk\Model\Consignment\AbstractConsignment[] $consignments
     *
     * @return string
     */
    private function apiEncodeReturnShipments(array $consignments): string
    {
        $data = [];

        foreach ($consignments as $consignment) {
            $shipment = [
                'parent'               => $consignment->getConsignmentId(),
                'reference_identifier' => $consignment->getReferenceIdentifier(),
                'carrier'              => $consignment->getCarrierId(),
                'email'                => $consignment->getEmail(),
                'name'                 => $consignment->getPerson(),
            ];

            $shipment = ConsignmentEncode::encodeExtraOptions($shipment, $consignment);

            $data['data']['return_shipments'][] = $shipment;
        }

        return json_encode($data);
    }

    /**
     * @param $result
     * @param $apiKey
     * @return self
     * @throws MissingFieldException
     */
    private function getNewCollectionFromResult(array $result, string $apiKey): self
    {
        $newCollection = new static();

        foreach ($result as $shipment) {
            $consignment = ConsignmentFactory::createByCarrierId($shipment['carrier_id'])->setApiKey($apiKey);

            //TODO: MY-32524 Make AbstractConsignmentAdapter for carrier specific exceptions
            if (CarrierUPSStandard::ID === $shipment['carrier_id'] || CarrierUPSExpressSaver::ID === $shipment['carrier_id']) {
                $shipment['barcode'] = $shipment['barcode'] ?: $shipment['external_identifier'];
            }

            $consignmentAdapter = new ConsignmentAdapter($shipment, $consignment);
            $isMultiCollo       = ! empty($shipment['secondary_shipments']);
            $newCollection->addConsignment($consignmentAdapter->getConsignment()->setMultiCollo($isMultiCollo));

            foreach ($shipment['secondary_shipments'] as $secondaryShipment) {
                $secondaryShipment  = Arr::arrayMergeRecursiveDistinct($shipment, $secondaryShipment);
                $consignment        = ConsignmentFactory::createByCarrierId($shipment['carrier_id'])->setApiKey($apiKey);
                $consignmentAdapter = new ConsignmentAdapter($secondaryShipment, $consignment);
                $newCollection->addConsignment($consignmentAdapter->getConsignment()->setMultiCollo($isMultiCollo));
            }
        }

        return $newCollection;
    }

    /**
     * @return void
     */
    private function addMissingReferenceId(): void
    {
        $this->transform(function (AbstractConsignment $consignment) {
            if (! $consignment->getReferenceIdentifier()) {
                $consignment->setReferenceIdentifier('random_' . uniqid('', true));
            }

            return $consignment;
        });
    }

    /**
     * @param mixed $id
     *
     * @return self
     */
    private function findByReferenceIdGroup($id): self
    {
        return $this->filter(function ($consignment) use ($id) {
            /**
             * @var AbstractConsignment $consignment
             */
            return Str::startsWith($consignment->getReferenceIdentifier(), $id);
        });
    }

    /**
     * Let the user of the SDK adjust the return consignment by means of a callback.
     *
     * @param array|AbstractConsignment[] $parentConsignments
     * @param \Closure|null               $modifier
     *
     * @return array|AbstractConsignment[]
     */
    private function getReturnConsignments(array $parentConsignments, ?Closure $modifier): array
    {
        $returnConsignments = [];

        foreach ($parentConsignments as $parentConsignment) {
            $returnConsignment = clone $parentConsignment;
            $returnConsignment->setDeliveryDate(null);
            if ($modifier) {
                $returnConsignment = $modifier($returnConsignment, $parentConsignment);
            }
            $returnConsignments[] = $returnConsignment;
        }

        return $returnConsignments;
    }

    /**
     * Fetches track & trace data for all consignments in the collection via the MyParcel API.
     * Groups consignments by API key, collects their consignment IDs, and retrieves the track & trace
     * data. Works with one or multiple consignments.
     *
     * @return $this
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     */
    public function fetchTrackTraceData(): self
    {
        $grouped =
            $this->where('consignment_id', '!=', null)
                ->groupBy(function (AbstractConsignment $item) {
                    return $item->getApiKey();
                });

        foreach ($grouped as $apiKey => $consignments) {
            $shipmentIds =
                $consignments->pluck('consignment_id')
                    ->all();
            $uri         = 'tracktraces/' . implode(';', $shipmentIds);
            $request     = (new MyParcelRequest())
                ->setRequestParameters($apiKey)
                ->sendRequest('GET', $uri);

            foreach ($request->getResult('data.tracktraces') as $trackTraceData) {
                $consignment = $this->getConsignmentByApiId($trackTraceData['shipment_id']);

                if ($consignment) {
                    $consignment->setHistory($trackTraceData['history'] ?? []);
                    $consignment->setTrackTraceUrl($trackTraceData['link_tracktrace'] ?? null);
                }
            }
        }

        return $this;
    }
}
