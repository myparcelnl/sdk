<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

enum CommonErrorUserCodeAuth : int
{
    case AccessDenied = 3000;
    case PermissionDenied = 3001;
    case SessionTokenNotFound = 3002;
    case LastLoginAttemptFailed = 3003;
    case MaxLoginAttemptsReached = 3004;
    case SessionTokenInvalidated = 3005;
    case InvalidJwtToken = 3006;
    case UserNotFoundInAuthcontext = 3007;
}