<?php

namespace MyParcelNL\Sdk\src\Services\Web;

class OrderWebService extends AbstractWebService
{
    public const ENDPOINT = 'fulfilment/orders';

    /**
     * @return array
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function getOrders(): array
    {
        $request = $this->createRequest()
            ->sendRequest('GET', self::ENDPOINT);

        return $request->getResult('data.orders');
    }

    /**
     * @param  string $orderUuid
     *
     * @return array
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function getOrder(string $orderUuid): array
    {
        $request = $this->createRequest()
            ->sendRequest('GET', sprintf('%s/%s', self::ENDPOINT, $orderUuid));

        return $request->getResult('data.orders.0');
    }
}
