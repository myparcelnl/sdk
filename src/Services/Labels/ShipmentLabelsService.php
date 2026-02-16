<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Labels;

use InvalidArgumentException;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Exception\ApiException;
use MyParcelNL\Sdk\Exception\MissingFieldException;
use MyParcelNL\Sdk\Helper\LabelHelper;
use MyParcelNL\Sdk\Model\MyParcelRequest;
use setasign\Fpdi\Fpdi;

final class ShipmentLabelsService
{
    use HasUserAgent;

    private const PREFIX_PDF_FILENAME = 'myparcel-label-';

    private string $apiKey;

    private string $labelLink = '';

    private string $labelPdf = '';

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param int[] $shipmentIds
     * @param mixed $positions
     */
    public function setLinkOfLabels(array $shipmentIds, $positions = 1): string
    {
        $this->validateIds($shipmentIds);

        $queryString = $this->buildLabelQuery($positions);
        $urlLocation = 'pdfs';
        $requestType = MyParcelRequest::REQUEST_TYPE_RETRIEVE_LABEL;

        if ($this->useLabelPrepare(count($shipmentIds))) {
            $requestType = MyParcelRequest::REQUEST_TYPE_RETRIEVE_PREPARED_LABEL;
            $urlLocation = 'pdf';
        }

        $request = (new MyParcelRequest())
            ->setUserAgents($this->getUserAgent())
            ->setRequestParameters(
                $this->apiKey,
                implode(';', $shipmentIds) . '/' . $queryString
            )
            ->sendRequest('GET', $requestType);

        $this->labelLink = (new MyParcelRequest())->getRequestUrl() . $request->getResult("data.{$urlLocation}.url");

        return $this->labelLink;
    }

    public function getLinkOfLabels(): string
    {
        return $this->labelLink;
    }

    /**
     * @param int[] $shipmentIds
     * @param mixed $positions
     *
     * @throws ApiException
     */
    public function setPdfOfLabels(array $shipmentIds, $positions = 1): string
    {
        $this->validateIds($shipmentIds);

        $queryString = $this->buildLabelQuery($positions);

        $request = (new MyParcelRequest())
            ->setUserAgents($this->getUserAgent())
            ->setRequestParameters(
                $this->apiKey,
                implode(';', $shipmentIds) . '/' . $queryString,
                MyParcelRequest::HEADER_ACCEPT_APPLICATION_PDF
            )
            ->sendRequest('GET', MyParcelRequest::REQUEST_TYPE_RETRIEVE_LABEL);

        $result = $request->getResult();

        if (! is_string($result) || ! preg_match('/^%PDF-\d/', $result)) {
            if (is_array($result) && isset($result['data']['payment_instructions'])) {
                throw new ApiException('Received payment link instead of pdf. Check your MyParcel account status.');
            }

            throw new ApiException('Did not receive expected pdf response. Please contact MyParcel.');
        }

        if (! class_exists(Fpdi::class)) {
            throw new ApiException('FPDI dependency is required to process label PDFs.');
        }

        $fpdi = new Fpdi();
        $fileResource = fopen('php://memory', 'rb+');
        fwrite($fileResource, $result);

        $pageCount = $fpdi->setSourceFile($fileResource);

        for ($i = 1; $i <= $pageCount; $i++) {
            $templateIndex = $fpdi->importPage($i);
            $specs = $fpdi->getTemplateSize($templateIndex);
            $fpdi->addPage($specs['orientation'], [$specs['width'], $specs['height']]);
            $fpdi->useTemplate($templateIndex);
        }

        $this->labelPdf = $fpdi->Output('S');

        fclose($fileResource);

        return $this->labelPdf;
    }

    public function getLabelPdf(): string
    {
        return $this->labelPdf;
    }

    /**
     * @throws MissingFieldException
     */
    public function downloadPdfOfLabels(bool $inlineDownload = false): void
    {
        if ('' === $this->labelPdf) {
            throw new MissingFieldException('First set label_pdf key with setPdfOfLabels() before running downloadPdfOfLabels()');
        }

        header('Content-Type: application/pdf');
        header('Content-Length: ' . strlen($this->labelPdf));
        header('Content-disposition: ' . ($inlineDownload ? 'inline' : 'attachment') . '; filename="' . self::PREFIX_PDF_FILENAME . gmdate('Y-M-d H-i-s') . '.pdf"');
        header('Cache-Control: public, must-revalidate, max-age=0');
        header('Pragma: public');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

        echo $this->labelPdf;
        exit;
    }

    public function useLabelPrepare(int $numberOfShipments): bool
    {
        return $numberOfShipments > MyParcelRequest::SHIPMENT_LABEL_PREPARE_ACTIVE_FROM;
    }

    /**
     * @param mixed $positions
     */
    private function buildLabelQuery($positions): string
    {
        if (is_numeric($positions)) {
            return '?format=A4&positions=' . LabelHelper::getPositions((int) $positions);
        }

        if (is_array($positions)) {
            return '?format=A4&positions=' . implode(';', $positions);
        }

        return '?format=A6';
    }

    /**
     * @param array<int, mixed> $ids
     */
    private function validateIds(array $ids): void
    {
        if (empty($ids)) {
            throw new InvalidArgumentException('At least one shipment ID is required');
        }

        foreach ($ids as $id) {
            if (! is_int($id) && ! (is_string($id) && ctype_digit($id))) {
                throw new InvalidArgumentException('Shipment IDs must be integers');
            }
        }
    }
}
