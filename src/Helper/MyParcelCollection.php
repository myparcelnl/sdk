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

namespace MyParcelNL\Sdk\src\Helper;

use BadMethodCallException;
use Closure;
use InvalidArgumentException;
use MyParcelNL\Sdk\src\Adapter\ConsignmentAdapter;
use MyParcelNL\Sdk\src\Concerns\HasUserAgent;
use MyParcelNL\Sdk\src\Exception\ApiException;
use MyParcelNL\Sdk\src\Exception\MissingFieldException;
use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierInstabox;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\BaseConsignment;
use MyParcelNL\Sdk\src\Model\MyParcelRequest;
use MyParcelNL\Sdk\src\Services\CollectionEncode;
use MyParcelNL\Sdk\src\Services\ConsignmentEncode;
use MyParcelNL\Sdk\src\Support\Arr;
use MyParcelNL\Sdk\src\Support\Collection;
use MyParcelNL\Sdk\src\Support\Str;

/**
 * Stores all data to communicate with the MyParcel API
 *
 * @property \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment[] $items
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
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
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

        if ($this->count() === 1) {
            return new static($this->items);
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
     * This is deprecated because there may be multiple consignments with the same reference id
     *
     * @param $id
     *
     * @return mixed
     * @deprecated Use getConsignmentsByReferenceId()->first() instead
     *
     */
    public function getConsignmentByReferenceId($id)
    {
        return $this->getConsignmentsByReferenceId($id)->first();
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
     * @param  AbstractConsignment $consignment
     *
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
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
     * @param  int[]  $ids
     * @param  string $apiKey
     *
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function addConsignmentByConsignmentIds($ids, $apiKey): self
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
     * @param  string[] $ids
     * @param  string   $apiKey
     *
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function addConsignmentByReferenceIds($ids, $apiKey): self
    {
        foreach ($ids as $referenceId) {
            $consignment = (new BaseConsignment())
                ->setApiKey($apiKey)
                ->setReferenceId($referenceId);

            $this->addConsignment($consignment);
        }

        return $this;
    }

    /**
     * @param AbstractConsignment $consignment
     * @param                     $amount
     *
     * @return self
     */
    public function addMultiCollo(AbstractConsignment $consignment, $amount): self
    {
        if ($amount > 1) {
            $consignment->setMultiCollo();
        }

        if ($consignment->isPartOfMultiCollo() && ! $consignment->getReferenceId()) {
            $consignment->setReferenceId('random_multi_collo_' . uniqid('', true));
        }

        for ($i = 1; $i <= $amount; $i++) {
            $this->push($consignment);
        }

        return $this;
    }

    /**
     * Create concepts in MyParcel.
     *
     * @return  $this
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function createConcepts(): self
    {
        $newConsignments = $this->where('consignment_id', '!=', null)->toArray();
        $this->addMissingReferenceId();

        /* @var MyParcelCollection $consignments */
        foreach ($this->where('consignment_id', null)->groupBy('api_key') as $consignments) {
            $data    = (new CollectionEncode($consignments))->encode();
            $request = (new MyParcelRequest())
                ->setUserAgents($this->getUserAgent())
                ->setRequestParameters(
                    $consignments->first()->apiKey,
                    $data,
                    MyParcelRequest::HEADER_CONTENT_TYPE_SHIPMENT
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
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
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
     * @param  int $size
     *
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function setLatestData($size = 300): self
    {
        $myParcelRequest = new MyParcelRequest();
        $params          = $myParcelRequest->getLatestDataParams($size, $this, $key);

        $request = $myParcelRequest
            ->setUserAgents($this->getUserAgent())
            ->setRequestParameters(
                $key,
                $params
            )
            ->sendRequest('GET');

        if ($request->getResult() === null) {
            throw new ApiException('Unknown Error in MyParcel API response');
        }

        $result        = $request->getResult('data.shipments');
        $newCollection = $this->getNewCollectionFromResult($result);

        $this->items = $newCollection->sortByCollection($this)->items;

        return $this;
    }

    /**
     * Get all the information about the last created shipments
     *
     * @param      $key
     * @param  int $size
     *
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @deprecated use MyParcelCollection::query($key, ['size' => 300]) instead
     */
    public function setLatestDataWithoutIds($key, $size = 300): self
    {
        $params = ['size' => $size];

        return self::query($key, $params);
    }

    /**
     * Get link of labels
     *
     * @param  int $positions           The position of the label on an A4 sheet. Set to false to create an A6 sheet.
     *                                  You can specify multiple positions by using an array. E.g. [2,3,4]. If you do
     *                                  not specify an array, but specify a number, the following labels will fill the
     *                                  ascending positions. Positioning is only applied on the first page with labels.
     *                                  All subsequent pages will use the default positioning [1,2,3,4].
     *
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
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
     * @param  int $positions           The position of the label on an A4 sheet. You can specify multiple positions by
     *                                  using an array. E.g. [2,3,4]. If you do not specify an array, but specify a
     *                                  number, the following labels will fill the ascending positions. Positioning is
     *                                  only applied on the first page with labels. All subsequent pages will use the
     *                                  default positioning [1,2,3,4].
     *
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function setPdfOfLabels($positions = self::DEFAULT_A4_POSITION): self
    {
        /** If $positions is not false, set paper size to A4 */
        $this
            ->createConcepts()
            ->setLabelFormat($positions);
        $conceptIds = $this->getConsignmentIds($key);

        if ($key) {
            $request = (new MyParcelRequest())
                ->setUserAgents($this->getUserAgent())
                ->setRequestParameters(
                    $key,
                    implode(';', $conceptIds) . '/' . $this->getRequestBody(),
                    MyParcelRequest::HEADER_ACCEPT_APPLICATION_PDF
                )
                ->sendRequest('GET', MyParcelRequest::REQUEST_TYPE_RETRIEVE_LABEL);

            $this->label_pdf = $request->getResult();
        }

        $this->setLatestData();

        return $this;
    }

    /**
     * Download labels
     *
     * @param  bool $inline_download
     *
     * @return void
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
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
     * Send return label to customer. The customer can pay and download the label.
     *
     * @param  bool          $sendMail
     * @param  \Closure|null $modifier
     *
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function generateReturnConsignments(bool $sendMail, Closure $modifier = null): self
    {
        // Be sure consignments are created
        $this->createConcepts();

        $parentConsignments = $this->getConsignments(false);
        $returnConsignments = $this->getReturnConsignments($parentConsignments, $modifier);

        $data        = $this->apiEncodeReturnShipments($returnConsignments);
        $apiKey      = $returnConsignments[0]->getApiKey();

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
     * Send return label to customer. The customer can pay and download the label.
     *
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @deprecated Use generateReturnConsignments instead
     */
    public function sendReturnLabelMails(): self
    {
        return $this->generateReturnConsignments(true);
    }

    /**
     * Get all consignment ids
     *
     * @param string|null $key
     *
     * @return array|null
     */
    public function getConsignmentIds(string &$key = null): ?array
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
     * @param  string $apiKey
     * @param  mixed  $parameters May be an array or object containing properties.
     *                            If query_data is an array, it may be a simple one-dimensional structure,
     *                            or an array of arrays (which in turn may contain other arrays).
     *                            If query_data is an object, then only public properties will be incorporated
     *                            into the result.
     *
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
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
     * @param  int    $id
     * @param  string $apiKey
     *
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public static function find(int $id, string $apiKey): self
    {
        return self::findMany([$id], $apiKey);
    }

    /**
     * @param  array  $consignmentIds
     * @param  string $apiKey
     *
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
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
     * @param  string $id
     * @param  string $apiKey
     *
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public static function findByReferenceId(string $id, string $apiKey): self
    {
        return self::findManyByReferenceId([$id], $apiKey);
    }

    /**
     * @param  array  $referenceIds
     * @param  string $apiKey
     *
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public static function findManyByReferenceId(array $referenceIds, string $apiKey): self
    {
        $collection = new static();

        foreach ($referenceIds as $id) {
            $consignment = new BaseConsignment();
            $consignment->setReferenceId($id);
            $consignment->setApiKey($apiKey);

            $collection->addConsignment($consignment);
        }

        $collection->setLatestData();

        return $collection;
    }

    /**
     * @param \MyParcelNL\Sdk\src\Helper\MyParcelCollection|\MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment[] $sortedCollection
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
     * Set label format settings        The position of the label on an A4 sheet. You can specify multiple positions by
     *                                  using an array. E.g. [2,3,4]. If you do not specify an array, but specify a
     *                                  number, the following labels will fill the ascending positions. Positioning is
     *                                  only applied on the first page with labels. All subsequent pages will use the
     *                                  default positioning [1,2,3,4].
     *
     * @param int|array|null $positions
     *
     * @return self
     */
    private function setLabelFormat($positions): self
    {
        /** If $positions is not false, set paper size to A4 */
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
     * @param \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment[] $consignments
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
     *
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    private function getNewCollectionFromResult($result): self
    {
        $newCollection = new static();
        /** @var AbstractConsignment $consignment */
        $consignment = $this->first();
        $apiKey      = $consignment->getApiKey();

        foreach ($result as $shipment) {
            $consignment = ConsignmentFactory::createByCarrierId($shipment['carrier_id'])->setApiKey($apiKey);

            //TODO: MY-32524 Make AbstractConsignmentAdapter for carrier specific exceptions
            if (CarrierInstabox::ID === $shipment['carrier_id']) {
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
            if (!$consignment->getReferenceId()) {
                $consignment->setReferenceId('random_' . uniqid('', true));
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
            return Str::startsWith($consignment->getReferenceId(), $id);
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
}
