<?php
/**
 * Stores all data to communicate with the MyParcel API
 *
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2017 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v0.1.0
 */

namespace MyParcelNL\Sdk\src\Helper;

use MyParcelNL\Sdk\src\Model\MyParcelConsignment;
use MyParcelNL\Sdk\src\Model\MyParcelRequest;
use MyParcelNL\Sdk\src\Model\Repository\MyParcelConsignmentRepository;


/**
 * Stores all data to communicate with the MyParcel API
 *
 * Class MyParcelCollection
 * @package Model
 */
class MyParcelCollection
{
    const PREFIX_REFERENCE_ID = 'REFERENCE_ID_';
    const PREFIX_PDF_FILENAME = 'myparcel-label-';

    /**
     * @var MyParcelConsignmentRepository[]
     */
    private $consignments = [];

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
    private $label_position = null;

    /**
     * Link to download the PDF
     *
     * @var string
     */
    private $label_link = null;

    /**
     * Label in PDF format
     *
     * @var string
     */
    private $label_pdf = null;

    /**
     * @var bool
     */
    private $return = false;

    private $user_agent = '';

	/**
	 * @param bool $keepKeys
	 *
	 * @return MyParcelConsignmentRepository[]
	 */
    public function getConsignments($keepKeys = true)
    {
	    if ( $keepKeys ) {
            return $this->consignments;
	    }

	    return array_values($this->consignments);
    }

    /**
     * Get one consignment
     *
     * @return \MyParcelNL\Sdk\src\Model\Repository\MyParcelConsignmentRepository|null
     * @throws \Exception
     */
    public function getOneConsignment()
    {
        if (count($this->getConsignments()) > 1) {
            throw new \Exception('Can\'t run getOneConsignment(): Multiple items found');
        }

        foreach ($this->getConsignments() as $consignment) {
            return $consignment;
        }

        return null;
    }

    /**
     * @param string $id
     *
     * @return MyParcelConsignmentRepository
     */
    public function getConsignmentByReferenceId($id)
    {
        // return if referenceId not is set as a key
        foreach ($this->getConsignments() as $consignment) {
            if ($consignment->getReferenceId() == $id) {
                return $consignment;
            }
        }

        return null;
    }

    /**
     * @param integer $id
     *
     * @return MyParcelConsignmentRepository
     */
    public function getConsignmentByApiId($id)
    {
        // return if ApiId not is set as a key
        foreach ($this->getConsignments() as $consignment) {
            if ($consignment->getMyParcelConsignmentId() == $id) {
                return $consignment;
            }
        }

        return null;
    }

    /**
     * @return string
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

    public function isReturn($return = true)
    {
        $this->return = $return;

        return $this;
    }

	/**
	 * @param \MyParcelNL\Sdk\src\Model\Repository\MyParcelConsignmentRepository $consignment
	 *
	 * @param bool $needReferenceId
	 *
	 * @return $this
	 * @throws \Exception
	 */
    public function addConsignment(MyParcelConsignmentRepository $consignment, $needReferenceId = true)
    {
        if ($consignment->getApiKey() === null) {
            throw new \Exception('First set the API key with setApiKey() before running addConsignment()');
        }

        if ($needReferenceId && !empty($this->consignments)) {
            if ($consignment->getReferenceId() === null) {
                 throw new \Exception('First set the reference id with setReferenceId() before running addConsignment() for multiple shipments');
            } elseif (key_exists($consignment->getReferenceId(), $this->consignments)) {
                 throw new \Exception('setReferenceId() must be unique. For example, do not use an ID of an order as an order has multiple shipments. In that case, use the shipment ID.');
            }
        }

        if ($consignment->getReferenceId()) {
            $this->consignments[self::PREFIX_REFERENCE_ID . $consignment->getReferenceId()] = $consignment;
        } else {
            $this->consignments[] = $consignment;
        }
        
        return $this;
    }

    /**
     * Create concepts in MyParcel
     *
     * @todo    Produce all the items in one time with reference ID.
     *
     * @return  $this
     * @throws  \Exception
     */
    public function createConcepts()
    {
        /* @var $consignments MyParcelConsignmentRepository[] */
        foreach ($this->getConsignmentsSortedByKey() as $key => $consignments) {
            foreach ($consignments as $consignment) {
                if ($consignment->getMyParcelConsignmentId() === null) {
                    $data = $this->apiEncode([$consignment]);
                    $request = (new MyParcelRequest())
                        ->setUserAgent($this->getUserAgent())
                        ->setRequestParameters(
                            $key,
                            $data,
                            MyParcelRequest::REQUEST_HEADER_SHIPMENT
                        )
                        ->sendRequest();

                    $consignment->setMyParcelConsignmentId($request->getResult()['data']['ids'][0]['id']);

                }
            }
        }

        return $this;
    }
    
     /**
     * Delete concepts in MyParcel
     *
     * @return  $this
     * @throws  \Exception
     */
    public function deleteConcepts()
    {
        /* @var $consignments MyParcelConsignmentRepository[] */
        foreach ($this->getConsignmentsSortedByKey() as $key => $consignments) {
            foreach ($consignments as $consignment) {
                if ($consignment->getMyParcelConsignmentId() !== null) {
                    $request = (new MyParcelRequest())
                        ->setUserAgent($this->getUserAgent())
                        ->setRequestParameters(
                            $key,
                            $consignment->getMyParcelConsignmentId(),
                            MyParcelRequest::REQUEST_HEADER_DELETE
                        )
                        ->sendRequest('DELETE');
                }
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
	 * @throws \Exception
	 */
    public function setLatestData($size = 300)
    {
        $consignmentIds = $this->getConsignmentIds($key);
        if ($consignmentIds !== null) {
            $params = implode(';', $consignmentIds) . '?size=' . $size;
        } else {
            $referenceIds = $this->getConsignmentReferenceIds($key);
            if ($referenceIds != null) {
                $params = '?reference_identifier=' . implode(';', $referenceIds) . '&size=' . $size;
            } else {
                return $this;
            }
        }

        $request = (new MyParcelRequest())
            ->setUserAgent($this->getUserAgent())
            ->setRequestParameters(
                $key,
                $params,
                MyParcelRequest::REQUEST_HEADER_RETRIEVE_SHIPMENT
            )
            ->sendRequest('GET');

        if ($request->getResult() === null) {
            throw new \Exception('Unable to transport data to MyParcel.');
        }

        $consignmentsToReplace = [];

        foreach ($request->getResult()['data']['shipments'] as $shipment) {
            $consignment = $this->getConsignmentByApiId($shipment['id']);
            if ($consignment === null) {
                $consignment = $this->getConsignmentByReferenceId($shipment['reference_identifier']);
            }

            $consignmentsToReplace[] = $consignment->apiDecode($shipment);
        }

        $this->clearConsignmentsCollection();

        foreach ($consignmentsToReplace as $consignmentToReplace) {
            $this->addConsignment($consignmentToReplace, false);
        }

        return $this;
    }

	/**
	 * Get all current data
	 *
	 * Set id and run this function to update all the information about this shipment
	 *
	 * @param $key
	 * @param int $size
	 *
	 * @return $this
	 * @throws \Exception
	 */
    public function setLatestDataWithoutIds($key, $size = 300)
    {
	    $params = '?size=' . $size;

        $request = (new MyParcelRequest())
            ->setUserAgent($this->getUserAgent())
            ->setRequestParameters(
                $key,
                $params,
                MyParcelRequest::REQUEST_HEADER_RETRIEVE_SHIPMENT
            )
            ->sendRequest('GET');

        if ($request->getResult() === null) {
            throw new \Exception('Unable to transport data to MyParcel.');
        }

        foreach ($request->getResult()['data']['shipments'] as $shipment) {
	        $consignment = new MyParcelConsignmentRepository();
            $consignment->setApiKey($key)->apiDecode($shipment);
	        $this->addConsignment($consignment, false);
        }


        return $this;
    }

    /**
     * Get link of labels
     *
     * @param array|int|bool $positions The position of the label on an A4 sheet. You can specify multiple positions by
     *                                  using an array. E.g. [2,3,4]. If you do not specify an array, but specify a
     *                                  number, the following labels will fill the ascending positions. Positioning is
     *                                  only applied on the first page with labels. All subsequent pages will use the
     *                                  default positioning [1,2,3,4].
     *
     * @return $this
     */
    public function setLinkOfLabels($positions = false)
    {
        /** If $positions is not false, set paper size to A4 */
        $this
            ->createConcepts()
            ->setA4($positions);

        $conceptIds = $this->getConsignmentIds($key);

        if ($key) {
            $request = (new MyParcelRequest())
                ->setUserAgent($this->getUserAgent())
                ->setRequestParameters(
                    $key,
                    implode(';', $conceptIds),
                    MyParcelRequest::REQUEST_HEADER_RETRIEVE_LABEL_LINK
                )
                ->sendRequest('GET', MyParcelRequest::REQUEST_TYPE_RETRIEVE_LABEL);

            $this->label_link = MyParcelRequest::REQUEST_URL . $request->getResult()['data']['pdfs']['url'];
        }

        $this
            ->setLatestData();

        return $this;
    }

    /**
     * Receive label PDF
     *
     * After setPdfOfLabels() apiId and barcode is present
     *
     * @param array|integer|bool $positions The position of the label on an A4 sheet. You can specify multiple positions by
     *                                  using an array. E.g. [2,3,4]. If you do not specify an array, but specify a
     *                                  number, the following labels will fill the ascending positions. Positioning is
     *                                  only applied on the first page with labels. All subsequent pages will use the
     *                                  default positioning [1,2,3,4].
     *
     * @return $this
     */
    public function setPdfOfLabels($positions = false)
    {
        /** If $positions is not false, set paper size to A4 */
        $this
            ->createConcepts()
            ->setA4($positions);
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
     * @return $this
     * @throws \Exception
     */
    public function downloadPdfOfLabels($inline_download = false)
    {
        if ($this->label_pdf == null) {
            throw new \Exception('First set label_pdf key with setPdfOfLabels() before running downloadPdfOfLabels()');
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
     * Send return label to custommer. The customer can pay and download the label.
     *
     * @throws \Exception
     * @return $this
     */
    public function sendReturnLabelMails()
    {
    	$parentConsignment = $this->getConsignments(false)[0];

        $apiKey = $parentConsignment->getApiKey();
        $data = $this->apiEncodeReturnShipments($parentConsignment);

        $request = (new MyParcelRequest())
            ->setUserAgent($this->getUserAgent())
            ->setRequestParameters(
                $apiKey,
                $data,
                MyParcelRequest::REQUEST_HEADER_RETURN
            )
            ->sendRequest('POST');

        $result = $request->getResult();

        if ($result === null) {
            throw new \Exception('Unable to connect to MyParcel.');
        }

        if (
            empty($result['data']['ids'][0]['id']) ||
            (int) $result['data']['ids'][0]['id'] < 1
        ) {
            throw new \Exception('Can\'t send retour label to customer. Please create an issue on GitHub or contact MyParcel; support@myparcel.nl. Note this request body: ' . $data);
        }

        return $this;
    }

    /**
     * Get all consignment ids
     *
     * @param $key
     *
     * @return array
     */
    private function getConsignmentIds(&$key)
    {
        $conceptIds = [];

        foreach ($this->getConsignments() as $consignment) {
            if ($consignment->getMyParcelConsignmentId()) {
                $conceptIds[] = $consignment->getMyParcelConsignmentId();
                $key = $consignment->getApiKey();
            }
        }

        if (empty($conceptIds)) {
            return null;
        }

        return $conceptIds;
    }

    /**
     * Get all consignment ids
     *
     * @param $key
     *
     * @return array
     */
    private function getConsignmentReferenceIds(&$key)
    {
        $referenceIds = [];
        foreach ($this->getConsignments() as $consignment) {
            if ($consignment->getReferenceId()) {
                $referenceIds[] = $consignment->getReferenceId();
                $key = $consignment->getApiKey();
            }
        }
        if (empty($referenceIds)) {
            return null;
        }

        return $referenceIds;
    }

    /**
     * Set label format settings        The position of the label on an A4 sheet. You can specify multiple positions by
     *                                  using an array. E.g. [2,3,4]. If you do not specify an array, but specify a
     *                                  number, the following labels will fill the ascending positions. Positioning is
     *                                  only applied on the first page with labels. All subsequent pages will use the
     *                                  default positioning [1,2,3,4].
     *
     * @param integer $positions
     *
     * @return $this
     */
    private function setA4($positions = 1)
    {
        /** If $positions is not false, set paper size to A4 */
        if (is_numeric($positions)) {
            /** Generating positions for A4 paper */
            $this->paper_size = 'A4';
            $this->label_position = $this->getPositions($positions);
        } elseif (is_array($positions)) {
            /** Set positions for A4 paper */
            $this->paper_size = 'A4';
            $this->label_position = implode(';', $positions);
        } else {
            /** Set paper size to A6 */
            $this->paper_size = 'A6';
            $this->label_position = null;
        }

        return $this;
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
        return $this->user_agent;
    }

    /**
     * @param string $platform
     * @param string $version
     * @internal param string $user_agent
     * @return $this
     */
    public function setUserAgent($platform, $version = null)
    {
        $this->user_agent = 'MyParcel-' . $platform;
        if ($version !== null) {
            $this->user_agent .= '/' . str_replace('v', '', $version);
        }

        return $this;
    }

    /**
     * Clear this collection
     */
    public function clearConsignmentsCollection() {
        $this->consignments = [];
    }

    /**
     * Encode multiple shipments so that the data can be sent to MyParcel.
     *
     * @param $consignments MyParcelConsignmentRepository[]
     *
     * @return string
     */
    private function apiEncode($consignments)
    {
        $data = [];

        foreach ($consignments as $consignment) {
            $data['data']['shipments'][] = $consignment->apiEncode();
        }

        return json_encode($data);
    }

    /**
     * Encode multiple ReturnShipment Objects
     *
     * @param MyParcelConsignmentRepository $consignment
     *
     * @return string
     */
    private function apiEncodeReturnShipments($consignment)
    {
        $data['data']['return_shipments'][] = $consignment->encodeReturnShipment();

        return json_encode($data);
    }

    /**
     * Generating positions for A4 paper
     *
     * @param int $start
     *
     * @return string
     */
    private function getPositions($start)
    {
        $aPositions = [];
        switch ($start) {
            case 1:
                $aPositions[] = 1;
            // No break
            case 2:
                $aPositions[] = 2;
            // No break
            case 3:
                $aPositions[] = 3;
            // No break
            case 4:
                $aPositions[] = 4;
                break;
        }

        return implode(';', $aPositions);
    }

    /**
     * @return MyParcelConsignmentRepository[]
     */
    private function getConsignmentsSortedByKey()
    {
        $aConsignments = [];
        /** @var $consignment MyParcelConsignment */
        foreach ($this->getConsignments() as $consignment) {
            $aConsignments[$consignment->getApiKey()][] = $consignment;
        }

        return $aConsignments;
    }
}
