<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Labels;

use GuzzleHttp\Client as GuzzleClient;
use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Exception\ApiException;
use MyParcelNL\Sdk\Exception\MissingFieldException;
use MyParcelNL\Sdk\Helper\LabelHelper;
use MyParcelNL\Sdk\Model\MyParcelRequest;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use setasign\Fpdi\Fpdi;

/**
 * Service for retrieving shipment labels (link/PDF) by shipment IDs.
 */
final class ShipmentLabelsService
{
    use HasUserAgent;

    private const PREFIX_PDF_FILENAME = 'myparcel-label-';
    private const LABEL_LINK_ACCEPT_HEADER = 'application/vnd.shipment_label_link+json';
    private const PDF_ACCEPT_HEADER = 'application/pdf';

    private ShipmentApi $api;
    private ClientInterface $httpClient;

    private string $labelLink = '';

    private string $labelPdf = '';

    public function __construct(
        string $apiKey,
        ?ShipmentApi $api = null,
        ?ClientInterface $httpClient = null,
        ?string $host = null
    ) {
        $this->api = $api ?? ShipmentApiFactory::make($apiKey, $host);
        $this->httpClient = $httpClient ?? new GuzzleClient(['timeout' => 10]);
    }

    /**
     * @param int[] $shipmentIds
     * @param mixed $positions
     *
     * @throws ApiException
     */
    public function setLinkOfLabels(array $shipmentIds, $positions = 1): string
    {
        $this->validateIds($shipmentIds);

        [$format, $resolvedPositions] = $this->resolveFormatAndPositions($positions);
        $request = $this->buildLabelsRequest($shipmentIds, $format, $resolvedPositions)
            ->withHeader('Accept', self::LABEL_LINK_ACCEPT_HEADER);
        $response = $this->httpClient->sendRequest($request);

        $decoded = json_decode((string) $response->getBody(), true);
        if (! is_array($decoded)) {
            throw new ApiException('Did not receive expected label link response. Please contact MyParcel.');
        }

        $url = $decoded['data']['pdfs']['url'] ?? $decoded['data']['pdf']['url'] ?? null;
        if (! is_string($url) || '' === $url) {
            throw new ApiException('Did not receive expected label link response. Please contact MyParcel.');
        }

        $this->labelLink = $this->resolveAbsoluteUrl($url, $request);

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

        [$format, $resolvedPositions] = $this->resolveFormatAndPositions($positions);
        $request = $this->buildLabelsRequest($shipmentIds, $format, $resolvedPositions)
            ->withHeader('Accept', self::PDF_ACCEPT_HEADER);
        $response = $this->httpClient->sendRequest($request);
        $result = (string) $response->getBody();

        if (! is_string($result) || ! preg_match('/^%PDF-\d/', $result)) {
            $decoded = json_decode($result, true);
            if (is_array($decoded) && isset($decoded['data']['payment_instructions'])) {
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

    /**
     * @deprecated Prepared labels are handled by the regular labels endpoint using Accept negotiation.
     *             This method remains for backward compatibility.
     */
    public function useLabelPrepare(int $numberOfShipments): bool
    {
        return $numberOfShipments > MyParcelRequest::SHIPMENT_LABEL_PREPARE_ACTIVE_FROM;
    }

    /**
     * Build label request using generated ShipmentApi request builder.
     *
     * @param int[] $shipmentIds
     */
    private function buildLabelsRequest(array $shipmentIds, string $format, ?string $positions): RequestInterface
    {
        return $this->api->getShipmentsLabelsRequest(
            implode(';', array_map('strval', $shipmentIds)),
            $this->getUserAgentHeader(),
            $format,
            $positions,
            null,
            null,
            ShipmentApi::contentTypes['getShipmentsLabels'][0]
        );
    }

    /**
     * @param mixed $positions
     * @return array{0: string, 1: string|null}
     */
    private function resolveFormatAndPositions($positions): array
    {
        if (is_numeric($positions)) {
            return ['A4', LabelHelper::getPositions((int) $positions)];
        }

        if (is_array($positions)) {
            return ['A4', implode(';', $positions)];
        }

        return ['A6', null];
    }

    private function resolveAbsoluteUrl(string $url, RequestInterface $request): string
    {
        if (preg_match('/^https?:\/\//i', $url)) {
            return $url;
        }

        $uri = $request->getUri();
        $base = $uri->getScheme() . '://' . $uri->getHost();
        if (null !== $uri->getPort()) {
            $base .= ':' . $uri->getPort();
        }

        return rtrim($base, '/') . '/' . ltrim($url, '/');
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
