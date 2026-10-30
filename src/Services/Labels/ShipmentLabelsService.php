<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Labels;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\GetShipmentsLabels;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Exception\ApiException;
use MyParcelNL\Sdk\Exception\MissingFieldException;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Service for retrieving shipment labels (link/PDF) by shipment IDs.
 *
 * The service keeps the link and PDF behaviour of v11: an unexpected response throws an
 * ApiException of the SDK. The generated getShipmentsLabels() does not read a PDF response, so
 * the service sends the generated endpoint with executeRawEndpoint() and reads the response itself.
 *
 * @todo Use getShipmentsLabels() for the link when the spec documents the PDF response of the labels endpoint.
 */
final class ShipmentLabelsService
{
    use HasUserAgent;

    private const PREFIX_PDF_FILENAME = 'myparcel-label-';
    private const LABEL_LINK_ACCEPT_HEADER = 'application/vnd.shipment_label_link+json';
    private const PDF_ACCEPT_HEADER = 'application/pdf';
    private const SHIPMENT_LABEL_PREPARE_ACTIVE_FROM = 25;
    private const A4_FIRST_LABEL_POSITION = 1;
    private const A4_LAST_LABEL_POSITION = 4;

    private Client $api;

    private string $labelLink = '';

    private string $labelPdf = '';

    /**
     * The third argument has the place of the PSR-18 client of v11, so the host stays the fourth argument.
     *
     * @param HttpClientInterface|null $httpClient Sends the requests when the service builds the client.
     */
    public function __construct(
        string $apiKey,
        ?Client $api = null,
        ?HttpClientInterface $httpClient = null,
        ?string $host = null
    ) {
        $this->api = $api ?? ShipmentApiFactory::client($apiKey, $host, null, [], $httpClient);
    }

    /**
     * Fetch and store the label download link for the given shipments.
     *
     * @param int[] $shipmentIds
     * @param mixed $positions Label positions (numeric for A4, array for explicit slots, other for A6).
     *
     * @throws ApiException
     */
    public function setLinkOfLabels(array $shipmentIds, $positions = 1): string
    {
        $response = $this->fetchLabels($shipmentIds, $positions, self::LABEL_LINK_ACCEPT_HEADER);

        $decoded = json_decode($response->getContent(false), true);
        if (! is_array($decoded)) {
            throw new ApiException('Did not receive expected label link response. Please contact MyParcel.');
        }

        $url = $decoded['data']['pdfs']['url'] ?? $decoded['data']['pdf']['url'] ?? null;
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
     * Fetch and store the raw PDF content for the given shipment labels.
     *
     * @param int[] $shipmentIds
     * @param mixed $positions Label positions (numeric for A4, array for explicit slots, other for A6).
     *
     * @throws ApiException
     */
    public function setPdfOfLabels(array $shipmentIds, $positions = 1): string
    {
        $result = $this->fetchLabels($shipmentIds, $positions, self::PDF_ACCEPT_HEADER)->getContent(false);

        if (! preg_match('/^%PDF-\d/', $result)) {
            $decoded = json_decode($result, true);
            if (is_array($decoded) && isset($decoded['data']['payment_instructions'])) {
                throw new ApiException('Received payment link instead of pdf. Check your MyParcel account status.');
            }

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
     * Send the generated labels endpoint with the given Accept header, and return the response as it is.
     *
     * @param int[] $shipmentIds
     * @param mixed $positions
     */
    private function fetchLabels(array $shipmentIds, $positions, string $accept): ResponseInterface
    {
        [$format, $resolvedPositions] = $this->resolveFormatAndPositions($positions);

        $query = ['format' => $format];
        if (null !== $resolvedPositions) {
            $query['positions'] = $resolvedPositions;
        }

        return $this->api->executeRawEndpoint(new GetShipmentsLabels(
            implode(';', array_map('strval', $shipmentIds)),
            $query,
            ['User-Agent' => $this->getUserAgentHeader()],
            ['Accept' => [$accept]]
        ));
    }

    /**
     * @param mixed $positions
     * @return array{0: string, 1: string|null}
     */
    private function resolveFormatAndPositions($positions): array
    {
        if (is_numeric($positions)) {
            $start = (int) $positions;

            return [
                'A4',
                $start >= self::A4_FIRST_LABEL_POSITION && $start <= self::A4_LAST_LABEL_POSITION
                    ? implode(';', range($start, self::A4_LAST_LABEL_POSITION))
                    : '',
            ];
        }

        if (is_array($positions)) {
            return ['A4', implode(';', $positions)];
        }

        return ['A6', null];
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
        $base  = ($parts['scheme'] ?? 'https') . '://' . ($parts['host'] ?? '');
        if (isset($parts['port'])) {
            $base .= ':' . $parts['port'];
        }

        return rtrim($base, '/') . '/' . ltrim($url, '/');
    }
}
