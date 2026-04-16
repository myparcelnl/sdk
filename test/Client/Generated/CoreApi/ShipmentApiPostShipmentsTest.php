<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client\Generated\CoreApi;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11Data;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentRequest;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Psr\Http\Message\RequestInterface;

final class ShipmentApiPostShipmentsTest extends TestCase
{
    public function testPostShipmentsDeserializesMultiColloRegionsAsStrings(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects(self::once())
            ->method('send')
            ->with(
                self::callback(function (RequestInterface $request): bool {
                    self::assertSame('POST', $request->getMethod());
                    self::assertSame('/shipments', $request->getUri()->getPath());

                    return true;
                }),
                self::isType('array')
            )
            ->willReturn(new Response(200, [], json_encode([
                'data' => [
                    'shipments' => [[
                        'id' => 123,
                        'reference_identifier' => 'multi-collo-ref',
                        'region' => 'NL',
                        'secondary_shipments' => [[
                            'id' => 124,
                            'reference_identifier' => 'multi-collo-ref',
                            'region' => 'NL',
                        ]],
                    ]],
                ],
            ], JSON_THROW_ON_ERROR)));

        $config = new Configuration();
        $config->setHost('https://api.myparcel.nl');
        $config->setAccessToken('encoded_api_key');

        $data = new ShipmentPostShipmentsRequestV11Data();
        $data->setShipments([new ShipmentRequest()]);
        $data->setUserAgent('SDK-Test/1.0');

        $request = new ShipmentPostShipmentsRequestV11();
        $request->setData($data);

        $api = new ShipmentApi($client, $config);
        $response = $api->postShipments('SDK-Test/1.0', $request);

        $shipments = $response->getData()->getShipments();

        self::assertCount(1, $shipments);
        self::assertSame('NL', $shipments[0]->getRegion());
        self::assertCount(1, $shipments[0]->getSecondaryShipments());
        self::assertSame('NL', $shipments[0]->getSecondaryShipments()[0]->getRegion());
    }

    public function testPostShipmentsDeserializesEmptyPaymentStatusAsString(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects(self::once())
            ->method('send')
            ->with(
                self::callback(function (RequestInterface $request): bool {
                    self::assertSame('POST', $request->getMethod());
                    self::assertSame('/shipments', $request->getUri()->getPath());

                    return true;
                }),
                self::isType('array')
            )
            ->willReturn(new Response(200, [], json_encode([
                'data' => [
                    'shipments' => [[
                        'id' => 123,
                        'reference_identifier' => 'return-ref',
                        'payment_status' => '',
                    ]],
                ],
            ], JSON_THROW_ON_ERROR)));

        $config = new Configuration();
        $config->setHost('https://api.myparcel.nl');
        $config->setAccessToken('encoded_api_key');

        $data = new ShipmentPostShipmentsRequestV11Data();
        $data->setShipments([new ShipmentRequest()]);
        $data->setUserAgent('SDK-Test/1.0');

        $request = new ShipmentPostShipmentsRequestV11();
        $request->setData($data);

        $api = new ShipmentApi($client, $config);
        $response = $api->postShipments('SDK-Test/1.0', $request);

        $shipments = $response->getData()->getShipments();

        self::assertCount(1, $shipments);
        self::assertSame('', $shipments[0]->getPaymentStatus());
    }
}
