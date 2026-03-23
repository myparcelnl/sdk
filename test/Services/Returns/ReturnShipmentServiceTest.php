<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Returns;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostReturnShipmentsRequest;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequest;
use MyParcelNL\Sdk\Services\Returns\ReturnShipmentService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestInterface;

final class ReturnShipmentServiceTest extends TestCase
{
    public function testCreateRelatedThrowsOnEmptyInput(): void
    {
        $service = new ReturnShipmentService(
            $this->getApiKey(),
            $this->createMock(ShipmentApi::class),
            $this->createMock(PsrClientInterface::class)
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one related return shipment is required');

        $service->createRelated([]);
    }

    public function testCreateRelatedDoesNotPerformLocalGeneratedModelValidation(): void
    {
        $baseRequest = new Request('POST', 'https://api.myparcel.nl/shipments');

        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::once())
            ->method('postShipmentsRequest')
            ->willReturn($baseRequest);

        $httpClient = $this->createMock(PsrClientInterface::class);
        $httpClient->expects(self::once())
            ->method('sendRequest')
            ->willReturn(new Response(200, [], json_encode([
                'data' => ['ids' => []],
            ])));

        $service = new ReturnShipmentService(
            $this->getApiKey(),
            $api,
            $httpClient
        );

        $result = $service->createRelated([[
            'carrier' => 1,
            'email' => 'test@example.com',
            'name' => 'John Doe',
        ]]);

        self::assertSame([], $result);
    }

    public function testCreateRelatedBuildsGeneratedRequestAddsSendMailQueryAndParsesResponse(): void
    {
        $baseRequest = new Request('POST', 'https://api.myparcel.nl/shipments?foo=bar');

        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::once())
            ->method('postShipmentsRequest')
            ->with(
                self::isType('string'),
                self::isInstanceOf(ShipmentPostReturnShipmentsRequest::class),
                self::isNull(),
                self::isNull(),
                self::isNull(),
                self::isNull(),
                self::identicalTo(ShipmentApi::contentTypes['postShipments'][2])
            )
            ->willReturn($baseRequest);

        $httpClient = $this->createMock(PsrClientInterface::class);
        $httpClient->expects(self::once())
            ->method('sendRequest')
            ->with(self::callback(static function (RequestInterface $request): bool {
                parse_str($request->getUri()->getQuery(), $query);

                self::assertSame('bar', $query['foo'] ?? null);
                self::assertSame('1', $query['send_return_mail'] ?? null);

                return true;
            }))
            ->willReturn(new Response(200, [], json_encode([
                'data' => [
                    'ids' => [
                        ['id' => 9001, 'reference_identifier' => 'ret-9001'],
                    ],
                ],
            ])));

        $service = new ReturnShipmentService($this->getApiKey(), $api, $httpClient);

        $result = $service->createRelated([[
            'parent' => 1001,
            'carrier' => 1,
            'email' => 'test@example.com',
            'name' => 'John Doe',
        ]], true);

        self::assertSame([9001 => 'ret-9001'], $result);
    }

    public function testCreateUnrelatedThrowsOnEmptyInput(): void
    {
        $service = new ReturnShipmentService(
            $this->getApiKey(),
            $this->createMock(ShipmentApi::class),
            $this->createMock(PsrClientInterface::class)
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one unrelated return shipment is required');

        $service->createUnrelated([]);
    }

    public function testCreateUnrelatedDoesNotPerformLocalGeneratedModelValidation(): void
    {
        $baseRequest = new Request('POST', 'https://api.myparcel.nl/shipments');

        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::once())
            ->method('postShipmentsRequest')
            ->willReturn($baseRequest);

        $httpClient = $this->createMock(PsrClientInterface::class);
        $httpClient->expects(self::once())
            ->method('sendRequest')
            ->willReturn(new Response(200, [], json_encode([
                'data' => ['ids' => []],
            ])));

        $service = new ReturnShipmentService(
            $this->getApiKey(),
            $api,
            $httpClient
        );

        $result = $service->createUnrelated([[
            'email' => 'test@example.com',
        ]]);

        self::assertSame([], $result);
    }

    public function testCreateUnrelatedBuildsGeneratedRequestAndParsesResponse(): void
    {
        $baseRequest = new Request('POST', 'https://api.myparcel.nl/shipments');

        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::once())
            ->method('postShipmentsRequest')
            ->with(
                self::isType('string'),
                self::isInstanceOf(ShipmentPostUnrelatedReturnShipmentsRequest::class),
                self::isNull(),
                self::isNull(),
                self::isNull(),
                self::isNull(),
                self::identicalTo(ShipmentApi::contentTypes['postShipments'][3])
            )
            ->willReturn($baseRequest);

        $httpClient = $this->createMock(PsrClientInterface::class);
        $httpClient->expects(self::once())
            ->method('sendRequest')
            ->with(self::callback(static function (RequestInterface $request): bool {
                parse_str($request->getUri()->getQuery(), $query);

                self::assertArrayNotHasKey('send_return_mail', $query);

                return true;
            }))
            ->willReturn(new Response(200, [], json_encode([
                'data' => [
                    'ids' => [
                        ['id' => 9101, 'reference_identifier' => 'unr-9101'],
                    ],
                ],
            ])));

        $service = new ReturnShipmentService($this->getApiKey(), $api, $httpClient);
        $result = $service->createUnrelated([[
            'carrier' => 1,
            'email' => 'test@example.com',
            'name' => 'Jane Doe',
        ]]);

        self::assertSame([9101 => 'unr-9101'], $result);
    }

    public function testCreateRelatedReturnsEmptyMappingWhenResponseHasNoIds(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::once())
            ->method('postShipmentsRequest')
            ->willReturn(new Request('POST', 'https://api.myparcel.nl/shipments'));

        $httpClient = $this->createMock(PsrClientInterface::class);
        $httpClient->expects(self::once())
            ->method('sendRequest')
            ->willReturn(new Response(200, [], json_encode([
                'data' => [],
            ])));

        $service = new ReturnShipmentService($this->getApiKey(), $api, $httpClient);
        $result = $service->createRelated([[
            'parent' => 1001,
            'carrier' => 1,
            'email' => 'test@example.com',
            'name' => 'John Doe',
        ]], false);

        self::assertSame([], $result);
    }
}
