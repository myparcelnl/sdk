<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class CommonResponsesProblem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * URI reference identifying the problem type. Derived from the wrapping UserException constant name (e.g. `urn:problem:invalid-shipments`) or, when no matching constant exists, a status-based fallback URN.
     * 
     *
     * @var string
     */
    public string $type;
    /**
     * A short, human-readable summary of the problem type.
     *
     * @var string
     */
    public string $title;
    /**
     * The HTTP status code for this occurrence.
     *
     * @var int
     */
    public int $status;
    /**
     * A human-readable explanation specific to this occurrence.
     *
     * @var string
     */
    public string $detail;
    /**
     * URI reference identifying the specific occurrence. The request path for the top-level object; the RFC 6901 JSON Pointer of the failing field for each `errors[]` entry.
     * 
     *
     * @var string
     */
    public string $instance;
    /**
     * Per-request correlation identifier. Mirrors the value of the `X-Request-Id` response header and the legacy envelope's `request_id` field. Allowed as an RFC 9457 §3.2 extension member so consumers don't have to read both header and body for tracing.
     * 
     *
     * @var string
     */
    public string $requestId;
    /**
     * Sub-problem list. Each entry is itself an RFC 9457 problem-detail object describing one failing field. The sub-error `type`/`title` narrow past the wrapping problem when the underlying validator code maps to its own UserException constant (e.g. `urn:problem:invalid-address`, `urn:problem:invalid-physical-properties`).
     * 
     *
     * @var list<CommonResponsesProblemErrorsItem>
     */
    public array $errors;
    public function definedProperties(): array
    {
        return ['type' => 'type', 'title' => 'title', 'status' => 'status', 'detail' => 'detail', 'instance' => 'instance', 'requestId' => 'request_id', 'errors' => 'errors'];
    }
}