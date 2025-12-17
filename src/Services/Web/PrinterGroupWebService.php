<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Web;

use MyParcelNL\Sdk\Model\Account\PrinterGroup;
use MyParcelNL\Sdk\Model\MyParcelRequest;
use MyParcelNL\Sdk\Support\Collection;

class PrinterGroupWebService extends AbstractWebService
{
    public const ENDPOINT = 'printer-groups';

    /**
     * Get all printer groups for the account
     *
     * @return \MyParcelNL\Sdk\Support\Collection|\MyParcelNL\Sdk\Model\Account\PrinterGroup[]
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     */
    public function getPrinterGroups(): Collection
    {
        $request = $this->createRequest()
            ->setBaseUrl(MyParcelRequest::PRINTING_API_URL)
            ->sendRequest('GET', self::ENDPOINT);

        $result = $request->getResult('results');

        if (!is_array($result)) {
            return new Collection();
        }

        return (new Collection($result))
            ->mapInto(PrinterGroup::class);
    }
}
