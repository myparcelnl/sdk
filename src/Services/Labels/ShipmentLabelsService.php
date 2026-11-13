<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Labels;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentLabelPrintingPosition;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentParametersPaperSize;
use MyParcelNL\Sdk\Client\Jane\GetShipmentsLabelsRaw;
use MyParcelNL\Sdk\Concerns\CreatesCoreApiClient;
use MyParcelNL\Sdk\Exception\ApiException;
use MyParcelNL\Sdk\Exception\MissingFieldException;

/**
 * Fetches the labels of shipments as a download link or as a PDF, through the Core API client.
 *
 * An error response throws the generated exceptions, such as `GetShipmentsLabelsNotFoundException`.
 * A 2xx response without a link or a PDF throws the `ApiException` of the SDK.
 */
final class ShipmentLabelsService
{
    use CreatesCoreApiClient;

    private const PREFIX_PDF_FILENAME = 'myparcel-label-';
    private const LABEL_LINK_ACCEPT_HEADER = 'application/vnd.shipment_label_link+json';
    // The spec documents the binary label as `application/*`. The API sends a PDF for this value.
    private const PDF_ACCEPT_HEADER = 'application/pdf';
    private const SHIPMENT_LABEL_PREPARE_ACTIVE_FROM = 25;

    private string $labelLink = '';

    private string $labelPdf = '';

    /**
     * Fetch and store the download link of the labels of the given shipments.
     *
     * @param int[] $shipmentIds
     * @param mixed $positions   The first label position on an A4 sheet, 1 to 4, or the positions as a
     *                           list. Another value gives A6 labels.
     *
     * @throws ApiException For a 2xx response without a link.
     */
    public function setLinkOfLabels(array $shipmentIds, mixed $positions = 1): string
    {
        $response = $this->api()->executeEndpoint(new GetShipmentsLabelsRaw(
            ids: self::idList($shipmentIds),
            queryParameters: $this->labelsQuery($positions),
            accept: ['Accept' => [self::LABEL_LINK_ACCEPT_HEADER]]
        ));
        $decoded  = json_decode($response->getContent(false), true);
        $url      = is_array($decoded) ? ($decoded['data']['pdfs']['url'] ?? $decoded['data']['pdf']['url'] ?? null) : null;

        if (! is_string($url) || '' === $url) {
            throw new ApiException('Did not receive expected label link response. Please contact MyParcel.');
        }

        $this->labelLink = $this->resolveAbsoluteUrl($url, (string) $response->getInfo('url'));

        return $this->labelLink;
    }

    /**
     * Return the previously fetched label link URL.
     */
    public function getLinkOfLabels(): string
    {
        return $this->labelLink;
    }

    /**
     * Fetch and store the PDF of the labels of the given shipments.
     *
     * @param int[] $shipmentIds
     * @param mixed $positions   The first label position on an A4 sheet, 1 to 4, or the positions as a
     *                           list. Another value gives A6 labels.
     *
     * @throws ApiException For a 2xx response that is not a PDF.
     */
    public function setPdfOfLabels(array $shipmentIds, mixed $positions = 1): string
    {
        $result = $this->api()->getShipmentsLabels(
            ids: self::idList($shipmentIds),
            queryParameters: $this->labelsQuery($positions),
            accept: ['Accept' => [self::PDF_ACCEPT_HEADER]]
        );

        if (! is_string($result) || ! preg_match('/^%PDF-\d/', $result)) {
            throw new ApiException('Did not receive expected pdf response. Please contact MyParcel.');
        }

        $this->labelPdf = $result;

        return $this->labelPdf;
    }

    /**
     * Return the previously fetched raw PDF label content.
     */
    public function getLabelPdf(): string
    {
        return $this->labelPdf;
    }

    /**
     * Stream the stored PDF to the browser as a download (or inline).
     *
     * @throws MissingFieldException If setPdfOfLabels() has not been called first.
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

    /**
     * @deprecated Prepared labels are handled by the regular labels endpoint using Accept negotiation.
     *             This method remains for backward compatibility.
     */
    public function useLabelPrepare(int $numberOfShipments): bool
    {
        return $numberOfShipments > self::SHIPMENT_LABEL_PREPARE_ACTIVE_FROM;
    }

    /**
     * Build the query parameters of the labels request.
     *
     * @return array{format: string, positions?: string}
     */
    private function labelsQuery(mixed $positions): array
    {
        [$format, $resolvedPositions] = $this->resolveFormatAndPositions($positions);

        return null === $resolvedPositions ? ['format' => $format] : ['format' => $format, 'positions' => $resolvedPositions];
    }

    /**
     * Turn the positions argument into the paper size and the `positions` parameter of the spec.
     *
     * A number is the first position on an A4 sheet: the labels take that position and the ones
     * after it. A number that is not a position of the spec leaves the parameter out.
     *
     * @return array{0: string, 1: string|null}
     */
    private function resolveFormatAndPositions(mixed $positions): array
    {
        if (is_numeric($positions)) {
            $first = RefShipmentLabelPrintingPosition::tryFrom((int) $positions);
            $last  = max(array_column(RefShipmentLabelPrintingPosition::cases(), 'value'));

            return [
                ShipmentParametersPaperSize::A4->value,
                null === $first ? null : implode(';', range($first->value, $last)),
            ];
        }

        if (is_array($positions)) {
            return [ShipmentParametersPaperSize::A4->value, implode(';', $positions)];
        }

        return [ShipmentParametersPaperSize::A6->value, null];
    }

    /**
     * Prefix a relative link with the scheme, host and port of the labels request.
     */
    private function resolveAbsoluteUrl(string $url, string $requestUrl): string
    {
        if (preg_match('/^https?:\/\//i', $url)) {
            return $url;
        }

        $parts = parse_url($requestUrl);
        $base  = $parts['scheme'] . '://' . $parts['host'];

        if (isset($parts['port'])) {
            $base .= ':' . $parts['port'];
        }

        return rtrim($base, '/') . '/' . ltrim($url, '/');
    }

}
