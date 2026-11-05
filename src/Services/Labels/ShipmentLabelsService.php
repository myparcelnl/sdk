<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Labels;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentLabelPrintingPosition;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentParametersPaperSize;
use MyParcelNL\Sdk\Client\Jane\GetShipmentsLabelsRaw;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Exception\ApiException;
use MyParcelNL\Sdk\Exception\MissingFieldException;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Fetches the labels of shipments as a download link or as a PDF, through the Core API client.
 *
 * An error response throws the generated exceptions, such as GetShipmentsLabelsNotFoundException.
 * A 2xx response without a link or a PDF throws the ApiException of the SDK.
 */
final class ShipmentLabelsService
{
    use HasUserAgent;

    private const PREFIX_PDF_FILENAME = 'myparcel-label-';
    private const LABEL_LINK_ACCEPT_HEADER = 'application/vnd.shipment_label_link+json';
    // The spec documents the binary label as `application/*`. The API sends a PDF for this value.
    private const PDF_ACCEPT_HEADER = 'application/pdf';
    private const SHIPMENT_LABEL_PREPARE_ACTIVE_FROM = 25;

    private ?Client $api;

    private string $apiKey;

    private ?string $host;

    private string $labelLink = '';

    private string $labelPdf = '';

    /**
     * Create the service with an API key, or with a client that keeps its own User-Agent.
     */
    public function __construct(string $apiKey, ?Client $api = null, ?string $host = null)
    {
        $this->apiKey = $apiKey;
        $this->api    = $api;
        $this->host   = $host;
    }

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
        $response = $this->fetchLabels($shipmentIds, $positions, self::LABEL_LINK_ACCEPT_HEADER);
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
        $result = $this->fetchLabels($shipmentIds, $positions, self::PDF_ACCEPT_HEADER)->getContent(false);

        if (! preg_match('/^%PDF-\d/', $result)) {
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
     * Send the labels request with the given Accept header, and return the 2xx response as it is.
     *
     * @param int[] $shipmentIds
     */
    private function fetchLabels(array $shipmentIds, mixed $positions, string $accept): ResponseInterface
    {
        [$format, $resolvedPositions] = $this->resolveFormatAndPositions($positions);

        $query = ['format' => $format];

        if (null !== $resolvedPositions) {
            $query['positions'] = $resolvedPositions;
        }

        return $this->api()->executeEndpoint(new GetShipmentsLabelsRaw(
            implode(';', array_map('strval', $shipmentIds)),
            $query,
            [],
            ['Accept' => [$accept]]
        ));
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

            $fromFirst = null === $first ? [] : array_filter(
                RefShipmentLabelPrintingPosition::cases(),
                static fn (RefShipmentLabelPrintingPosition $position): bool => $position->value >= $first->value
            );

            return [
                ShipmentParametersPaperSize::A4->value,
                [] === $fromFirst ? null : implode(';', array_map(static fn (RefShipmentLabelPrintingPosition $position): int => $position->value, $fromFirst)),
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

    /**
     * The first request creates the client with the User-Agent of the service, so a proposition that
     * is set after the constructor reaches the API.
     */
    private function api(): Client
    {
        return $this->api ??= ShipmentApiFactory::client($this->apiKey, $this->host, $this->getUserAgentHeader());
    }
}
