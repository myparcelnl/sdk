<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services\Web;

use MyParcelNL\Sdk\src\Model\Account\Account;

class AccountWebService extends AbstractWebService
{
    public const ENDPOINT = 'accounts';

    /**
     * @return \MyParcelNL\Sdk\src\Model\Account\Account
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function getAccount(): Account
    {
        $request = $this->createRequest()
            ->sendRequest('GET', self::ENDPOINT);

        $result = $request->getResult('data.accounts.0');

        return new Account($result);
    }
}
