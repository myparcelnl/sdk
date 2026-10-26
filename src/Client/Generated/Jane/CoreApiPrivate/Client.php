<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate;

class Client extends \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\Client\Client
{
    /**
     * Return the shipping rules visible to the authenticated account. When `shop_ids[]` is supplied, the result is filtered to those shops.
     *
     * @param array{
     *    "page"?: int, //Request a specific page of the results, used for paginated results.
     *    "size"?: int, //Number of shipping rules returned per page. Maximum 500.
     *    "shop_ids"?: array, //Filter shipping rules by shop. Repeat the parameter for multiple shops.
     *    "type"?: string, //Filter by shipping rule type.
     * } $queryParameters
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\GetShippingRulesClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\GetShippingRulesServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesShippingRulesPaginated
     */
    public function getShippingRules(array $queryParameters = [], array $headerParameters = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Endpoint\GetShippingRules($queryParameters, $headerParameters));
    }
    /**
     * Update one or more existing shipping rules in place.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPatchShippingRules $requestBody
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\PatchShippingRulesClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\PatchShippingRulesServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesShippingRules
     */
    public function patchShippingRules(\MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPatchShippingRules $requestBody, array $headerParameters = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Endpoint\PatchShippingRules($requestBody, $headerParameters));
    }
    /**
     * Create one or more shipping rules in a single request.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPostShippingRules $requestBody
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\PostShippingRulesClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\PostShippingRulesServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesShippingRules
     */
    public function postShippingRules(\MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPostShippingRules $requestBody, array $headerParameters = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Endpoint\PostShippingRules($requestBody, $headerParameters));
    }
    /**
     * Replace one or more existing shipping rules.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPutShippingRules $requestBody
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\PutShippingRulesClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\PutShippingRulesServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesShippingRules
     */
    public function putShippingRules(\MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPutShippingRules $requestBody, array $headerParameters = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Endpoint\PutShippingRules($requestBody, $headerParameters));
    }
    /**
     * Retrieve one or more shipping rules by their numeric IDs. Multiple IDs can be supplied by separating them with `;`.
     *
     * @param mixed $shippingRuleIds One or more shipping rule IDs. Separate multiple IDs using `;`.
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\GetShippingRulesByIdsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\GetShippingRulesByIdsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesShippingRules
     */
    public function getShippingRulesByIds($shippingRuleIds, array $headerParameters = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Endpoint\GetShippingRulesByIds($shippingRuleIds, $headerParameters));
    }
    /**
     * Delete a single shipping rule by id.
     * @param int $shippingRuleId A single shipping rule ID.
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\DeleteShippingRuleClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\DeleteShippingRuleServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\BadResponseException
     *
     * @return null
     */
    public function deleteShippingRule(int $shippingRuleId, array $headerParameters = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Endpoint\DeleteShippingRule($shippingRuleId, $headerParameters));
    }
    /**
     * Return the shipping rules attached to a single shop.
     * @param int $shopId The shop the shipping rules belong to.
     * @param array{
     *    "page"?: int, //Request a specific page of the results, used for paginated results.
     *    "size"?: int, //Number of shipping rules returned per page. Maximum 500.
     *    "type"?: string, //Filter by shipping rule type.
     * } $queryParameters
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\GetShippingRulesByShopClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\GetShippingRulesByShopServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesShippingRulesPaginated
     */
    public function getShippingRulesByShop(int $shopId, array $queryParameters = [], array $headerParameters = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Endpoint\GetShippingRulesByShop($shopId, $queryParameters, $headerParameters));
    }
    /**
     * Resolve which implications (carrier, contract, shipment options, physical properties) apply to a shop given a criteria filter. Implications are derived from the shop's shipping rules with a fallback per region.
     *
     * @param int $shopId The shop the shipping rules belong to.
     * @param array{
     *    "country"?: string, //Filter implications by recipient country (ISO 3166-1 alpha-2, case-insensitive).
     *    "region"?: string, //Filter by criteria region.
     *    "type"?: string, //Filter by shipping rule type.
     * } $queryParameters
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\GetShippingRuleImplicationsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\GetShippingRuleImplicationsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesImplications
     */
    public function getShippingRuleImplications(int $shopId, array $queryParameters = [], array $headerParameters = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Endpoint\GetShippingRuleImplications($shopId, $queryParameters, $headerParameters));
    }
    /**
     * @param list<callable(\Symfony\Contracts\HttpClient\HttpClientInterface): \Symfony\Contracts\HttpClient\HttpClientInterface> $additionalPlugins HttpClientInterface decorator factories, applied left-to-right after the server URL decorator
     * @param list<\Symfony\Component\Serializer\Normalizer\NormalizerInterface|\Symfony\Component\Serializer\Normalizer\DenormalizerInterface> $additionalNormalizers
     */
    public static function create(?\Symfony\Contracts\HttpClient\HttpClientInterface $httpClient = null, array $additionalPlugins = [], array $additionalNormalizers = [], bool $applyServerPlugins = true)
    {
        $plugins = [];
        if (null === $httpClient) {
            $httpClient = \Symfony\Component\HttpClient\HttpClient::create();
        }
        if ($applyServerPlugins) {
            $plugins[] = new \Jane\Component\OpenApiRuntime\Client\Plugin\ServerUrlHttpClient('https://api.myparcel.nl/');
        }
        if (count($additionalPlugins) > 0) {
            $plugins = array_merge($plugins, $additionalPlugins);
        }
        foreach ($plugins as $plugin) {
            $httpClient = $plugin($httpClient);
        }
        $normalizers = [new \Symfony\Component\Serializer\Normalizer\ArrayDenormalizer(), new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\JaneObjectNormalizer()];
        if (count($additionalNormalizers) > 0) {
            $normalizers = array_merge($normalizers, $additionalNormalizers);
        }
        $serializer = new \Symfony\Component\Serializer\Serializer($normalizers, [new \Symfony\Component\Serializer\Encoder\JsonEncoder(new \Symfony\Component\Serializer\Encoder\JsonEncode(), new \Symfony\Component\Serializer\Encoder\JsonDecode(['json_decode_associative' => true])), new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\Client\FormEncoder()]);
        return new static($httpClient, $serializer);
    }
}