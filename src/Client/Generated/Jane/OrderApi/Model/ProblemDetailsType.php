<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

enum ProblemDetailsType : string
{
    case AboutBlank = 'about:blank';
    case UrnProblemClient = 'urn:problem:client';
    case UrnProblemInvalidRequestSyntax = 'urn:problem:invalid-request-syntax';
    case UrnProblemNotFound = 'urn:problem:not-found';
    case UrnProblemServer = 'urn:problem:server';
    case UrnProblemUnauthorized = 'urn:problem:unauthorized';
    case UrnProblemUnsupportedMediaType = 'urn:problem:unsupported-media-type';
}