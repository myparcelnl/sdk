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

use Exception;
use http\Exception\BadMethodCallException;
use InvalidArgumentException;
use MyParcelNL\Sdk\src\Adapter\ConsignmentAdapter;
use MyParcelNL\Sdk\src\Exception\ApiException;
use MyParcelNL\Sdk\src\Exception\MissingFieldException;
use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\MyParcelRequest;
use MyParcelNL\Sdk\src\Services\CollectionEncode;
use MyParcelNL\Sdk\src\Services\ConsignmentEncode;
use MyParcelNL\Sdk\src\Support\Arr;
use MyParcelNL\Sdk\src\Support\Collection;
use MyParcelNL\Sdk\src\Support\Str;

/**
 * Stores all data to communicate with the MyParcel API
 *
 * Class MyParcelCollection
 */
class MyParcelCollection extends Collection
{
    const PREFIX_PDF_FILENAME = 'myparcel-label-';
    const DEFAULT_A4_POSITION = 1;

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
     * @var string
     */
    private static $user_agent;

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
     * @return mixed
     *
     * @throws BadMethodCallException
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
     * @return MyParcelCollection
     * @throws InvalidArgumentException
     */
    public function getConsignmentsByReferenceId($id): MyParcelCollection
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
     * @return MyParcelCollection
     */
    public function getConsignmentsByReferenceIdGroup($groupId): MyParcelCollection
    {
        return $this->findByReferenceIdGroup($groupId);
    }

    /**
     * This is deprecated because there may be multiple consignments with the same reference id
     *
     * @param $id
     *
     * @return mixed
     * @throws Exception
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
     * @param AbstractConsignment $consignment
     *
     * @return $this
     * @throws MissingFieldException
     */
    public function addConsignment(AbstractConsignment $consignment)
    {
        if ($consignment->getApiKey() === null) {
            throw new MissingFieldException('First set the API key with setApiKey() before running addConsignment()');
        }

        $consignment->validate();

        $this->push($consignment);

        return $this;
    }

    /**
     * @param int[]  $ids
     * @param string $apiKey
     *
     * @return self
     * @throws Exception
     */
    public function addConsignmentByConsignmentIds($ids, $apiKey)
    {
        foreach ($ids as $consignmentId) {
            $consignment = (new AbstractConsignment())
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
     * @throws Exception
     */
    public function addConsignmentByReferenceIds($ids, $apiKey)
    {
        foreach ($ids as $referenceId) {
            $consignment = (new AbstractConsignment())
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
     * @return MyParcelCollection
     */
    public function addMultiCollo(AbstractConsignment $consignment, $amount): self
    {
        $i = 1;

        if ($amount > 1) {
            $consignment->setMultiCollo();
        }

        if ($consignment->isPartOfMultiCollo() && null == $consignment->getReferenceId()) {
            $consignment->setReferenceId('random_multi_collo_' . uniqid());
        }

        while ($i <= $amount) {
            $this->push($consignment);
            $i++;
        }

        return $this;
    }

    /**
     * Create concepts in MyParcel.
     *
     * @return  $this
     * @throws MissingFieldException
     * @throws ApiException
     */
    public function createConcepts(): self
    {
        $newConsignments = $this->where('consignment_id', '!=', null)->toArray();
        $this->addMissingReferenceId();

        /* @var $consignments MyParcelCollection */
        foreach ($this->where('consignment_id', null)->groupBy('api_key') as $consignments) {
            $data    = (new CollectionEncode($consignments))->encode();
            $request = (new MyParcelRequest())
                ->setUserAgent($this->getUserAgent())
                ->setRequestParameters(
                    $consignments->first()->api_key,
                    $data,
                    MyParcelRequest::REQUEST_HEADER_SHIPMENT
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
     * @throws  Exception
     */
    public function deleteConcepts()
    {
        /* @var $consignments AbstractConsignment[] */
        foreach ($this->groupBy('api_key')->where('consignment_id', '!=', null) as $key => $consignments) {
            foreach ($consignments as $consignment) {
                (new MyParcelRequest())
                    ->setUserAgent($this->getUserAgent())
                    ->setRequestParameters(
                        $key,
                        (string) $consignment->getConsignmentId(),
                        MyParcelRequest::REQUEST_HEADER_DELETE
                    )
                    ->sendRequest('DELETE');
            }
        }

        return $this;
    }

    /**
     * Get all current data
     *
     * Set id and run this function to update all the information about this shipment
     *
     * @param int $size
     *
     * @return $this
     * @throws Exception
     */
    public function setLatestData($size = 300)
    {
        $myParcelRequest = new MyParcelRequest();
        $params          = $myParcelRequest->getLatestDataParams($size, $this, $key);

        $request = $myParcelRequest
            ->setUserAgent($this->getUserAgent())
            ->setRequestParameters(
                $key,
                $params,
                MyParcelRequest::REQUEST_HEADER_RETRIEVE_SHIPMENT
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
     * @param     $key
     * @param int $size
     *
     * @return $this
     * @throws ApiException
     * @throws MissingFieldException
     * @throws Exception
     * @deprecated use MyParcelCollection::query($key, ['size' => 300]) instead
     */
    public function setLatestDataWithoutIds($key, $size = 300)
    {
        $params = ['size' => $size];

        return self::query($key, $params);
    }

    /**
     * Get link of labels
     *
     * @param int $positions The position of the label on an A4 sheet. Set to false to create an A6 sheet.
     *                                  You can specify multiple positions by using an array. E.g. [2,3,4]. If you do
     *                                  not specify an array, but specify a number, the following labels will fill the
     *                                  ascending positions. Positioning is only applied on the first page with labels.
     *                                  All subsequent pages will use the default positioning [1,2,3,4].
     *
     * @return $this
     * @throws Exception
     */
    public function setLinkOfLabels($positions = self::DEFAULT_A4_POSITION)
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
                ->setUserAgent($this->getUserAgent())
                ->setRequestParameters(
                    $key,
                    implode(';', $conceptIds) . '/' . $this->getRequestBody(),
                    MyParcelRequest::REQUEST_HEADER_RETRIEVE_LABEL_LINK
                )
                ->sendRequest('GET', $requestType);

            $this->label_link = MyParcelRequest::REQUEST_URL . $request->getResult("data.$urlLocation.url");
        }

        $this->setLatestData();

        return $this;
    }

    /**
     * Receive label PDF
     *
     * After setPdfOfLabels() apiId and barcode is present
     *
     * @param int $positions The position of the label on an A4 sheet. You can specify multiple positions by
     *                                  using an array. E.g. [2,3,4]. If you do not specify an array, but specify a
     *                                  number, the following labels will fill the ascending positions. Positioning is
     *                                  only applied on the first page with labels. All subsequent pages will use the
     *                                  default positioning [1,2,3,4].
     *
     * @return $this
     * @throws Exception
     */
    public function setPdfOfLabels($positions = self::DEFAULT_A4_POSITION)
    {
        /** If $positions is not false, set paper size to A4 */
        $this
            ->createConcepts()
            ->setLabelFormat($positions);
        $conceptIds = $this->getConsignmentIds($key);

        if ($key) {
            $request = (new MyParcelRequest())
                ->setUserAgent($this->getUserAgent())
                ->setRequestParameters(
                    $key,
                    implode(';', $conceptIds) . '/' . $this->getRequestBody(),
                    MyParcelRequest::REQUEST_HEADER_RETRIEVE_LABEL_PDF
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
     * @param bool $inline_download
     *
     * @return void
     * @throws Exception
     */
    public function downloadPdfOfLabels($inline_download = false)
    {
        if ($this->label_pdf == null) {
            throw new MissingFieldException('First set label_pdf key with setPdfOfLabels() before running downloadPdfOfLabels()');
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
     * @param bool          $sendMail
     * @param \Closure|null $modifier
     *
     * @return $this
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function generateReturnConsignments(bool $sendMail, \Closure $modifier = null): self
    {
        // Be sure consignments are created
        $this->createConcepts();

        $parentConsignments = $this->getConsignments(false);
        $returnConsignments = $this->getReturnConsignments($parentConsignments, $modifier);

        $data        = $this->apiEncodeReturnShipments($returnConsignments);
        $apiKey      = $returnConsignments[0]->getApiKey();
        $requestType = MyParcelRequest::REQUEST_TYPE_SHIPMENTS;

        $request = (new MyParcelRequest())
            ->setUserAgent($this->getUserAgent())
            ->setRequestParameters(
                $apiKey,
                $data,
                MyParcelRequest::REQUEST_HEADER_RETURN
            )
            ->setQuery(['send_return_mail' => (int) $sendMail])
            ->sendRequest('POST', $requestType);

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
     * @return $this
     * @throws ApiException
     * @throws MissingFieldException
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
     * @inheritdoc
     */
    public function getRequestBody()
    {
        $body = $this->paper_size == 'A4' ? '?format=A4&positions=' . $this->label_position : '?format=A6';

        return $body;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this::$user_agent;
    }

    /**
     * @param string      $platform
     * @param string|null $version
     *
     * @return self
     * @internal param string $user_agent
     * @deprecated Use setCustomUserAgent instead
     */
    public function setUserAgent(string $platform, string $version = null): self
    {
        $this::$user_agent = 'MyParcel-' . $platform;
        if ($version !== null) {
            $this::$user_agent .= '/' . str_replace('v', '', $version);
        }

        return $this;
    }

    /**
     * @param  array $userAgentMap
     *
     * @return self
     */
    public function setUserAgents(array $userAgentMap): self
    {
        $userAgents = [];

        foreach ($userAgentMap as $key => $value) {
            if (Str::startsWith($value, 'v')) {
                $value = str_replace('v', '', $value);
            }

            $userAgents[] = $key . '/' . $value;
        }

        self::$user_agent = implode(' ', $userAgents);

        return $this;
    }

    /**
     * Clear this collection
     */
    public function clearConsignmentsCollection()
    {
        $this->items = [];
    }

    /**
     * To search and filter consignments by certain values
     *
     * @param string $apiKey
     * @param mixed  $parameters May be an array or object containing properties.
     *                           If query_data is an array, it may be a simple one-dimensional structure,
     *                           or an array of arrays (which in turn may contain other arrays).
     *                           If query_data is an object, then only public properties will be incorporated
     *                           into the result.
     *
     * @return \MyParcelNL\Sdk\src\Helper\MyParcelCollection
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public static function query(string $apiKey, $parameters): MyParcelCollection
    {
        $collection = new static();

        // The field `size` is required to prevent bugs. Think carefully about what
        // the maximum size should be in your use case. If you want to pick up all
        // open consignments for example, you would probably want to adjust size to 300.
        if (empty($parameters['size'])) {
            throw new MissingFieldException('Field "size" is required.');
        }

        $request = (new MyParcelRequest())
            ->setRequestParameters(
                $apiKey,
                null,
                MyParcelRequest::REQUEST_HEADER_RETRIEVE_SHIPMENT
            )
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
     * @return MyParcelCollection
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public static function find(int $id, string $apiKey): MyParcelCollection
    {
        return self::findMany([$id], $apiKey);
    }

    /**
     * @param array  $consignmentIds
     * @param string $apiKey
     *
     * @return MyParcelCollection
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public static function findMany(array $consignmentIds, string $apiKey): MyParcelCollection
    {
        $collection = new static();

        foreach ($consignmentIds as $id) {
            $consignment = new AbstractConsignment();
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
     * @return MyParcelCollection
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public static function findByReferenceId(string $id, string $apiKey): MyParcelCollection
    {
        return self::findManyByReferenceId([$id], $apiKey);
    }

    /**
     * @param array  $referenceIds
     * @param string $apiKey
     *
     * @return MyParcelCollection
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public static function findManyByReferenceId(array $referenceIds, string $apiKey): MyParcelCollection
    {
        $collection = new static();

        foreach ($referenceIds as $id) {
            $consignment = new AbstractConsignment();
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
     * @return $this
     */
    public function sortByCollection(MyParcelCollection $sortedCollection): MyParcelCollection
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
     * @return $this
     */
    private function setLabelFormat($positions)
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
                'parent'  => $consignment->getConsignmentId(),
                'carrier' => $consignment->getCarrierId(),
                'email'   => $consignment->getEmail(),
                'name'    => $consignment->getPerson(),
            ];

            $shipment = ConsignmentEncode::encodeExtraOptions($shipment, $consignment);

            $data['data']['return_shipments'][] = $shipment;
        }

        return json_encode($data);
    }

    /**
     * @param $result
     *
     * @return MyParcelCollection
     * @throws Exception
     */
    private function getNewCollectionFromResult($result)
    {
        $newCollection = new static();
        /** @var AbstractConsignment $consignment */
        $consignment = $this->first();
        $apiKey      = $consignment->getApiKey();

        foreach ($result as $shipment) {
            $consignment        = ConsignmentFactory::createByCarrierId($shipment['carrier_id'])->setApiKey($apiKey);
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
            if (null == $consignment->getReferenceId()) {
                $consignment->setReferenceId('random_' . uniqid());
            }

            return $consignment;
        });
    }

    /**
     * @param mixed $id
     *
     * @return MyParcelCollection
     */
    private function findByReferenceIdGroup($id): MyParcelCollection
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
    private function getReturnConsignments(array $parentConsignments, ?\Closure $modifier): array
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
