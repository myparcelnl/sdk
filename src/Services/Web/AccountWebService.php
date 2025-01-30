<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Web;

use MyParcelNL\Sdk\Model\Account\Account;

class AccountWebService extends AbstractWebService
{
    public const ENDPOINT = 'accounts';

    /**
     * @return \MyParcelNL\Sdk\Model\Account\Account
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     */
    public function getAccount(): Account
    {
        $request = $this->createRequest()
            ->sendRequest('GET', self::ENDPOINT);

        $result = $request->getResult('data.accounts.0');

        return new Account($result);
    }
}
