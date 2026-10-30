<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi;

class Client extends \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\Client
{
    /**
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetIndexServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\GetResponse200
     */
    public function getIndex(array $headerParameters = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\GetIndex($headerParameters));
    }
    /**
     * Get the delivery options for a given location and carrier.
     *
     * If none of the optional parameters are specified then the following
     * default will be used: If a request is made for the delivery options
     * between Friday after the default cutoff_time (15:30) and Monday before
     * the default cutoff_time (15:30) then Tuesday will be shown as the next
     * possible delivery date.
     *
     * @param array{
     *    "cc"?: string, //The country code for which to fetch the delivery options.
     *    "postal_code"?: string, //The postal code for which to fetch the resources.
     *    "number"?: string, //The street number for which to fetch the resources.
     *    "city"?: string, //Only available for carriers Bpost and DPD. This can be used to narrow the search results for locations outside NL.
     *    "street"?: string, //This can be used to narrow the search results for locations outside NL.
     *    "platform"?: string, //The platform where you want the data from.
     *    "shop_id"?: mixed,
     *    "carrier"?: string,
     *    "delivery_date"?: string, //The date on which the package has to be delivered.
     *    "delivery_time"?: string, //The time on which a package has to be delivered.
     *                              //> __Note__: This is only an indication of time the package will be
     *                              //>           delivered on the selected date.
     *    "cutoff_time"?: string, //This option allows the **Merchant** to indicate the latest cutoff time before which a consumer order will still be picked, packed and dispatched on the same/first set drop-off day, taking into account the drop-off delay. Default time is 15:30. For example, if cutoff time is 15:30, Monday is a delivery day and there's no delivery delay; all orders placed Monday before 15:30 will be dropped of at PostNL on that same Monday in time for the Monday collection.
     *    "dropoff_days"?: string, //This options allows the **Merchant** to set the days she normally goes to PostNL to hand in her parcels. By default Saturday and Sunday are excluded.
     *    "monday_delivery"?: mixed, //Monday delivery is only possible when the package is delivered before
     *                               //15.00 on Saturday at the designated PostNL locations.
     *                               //> __Note__: To activate Monday delivery, value 6 must be given with
     *                               //> [dropoff_days], value 1 must be given by [monday_delivery]. And on
     *                               //> Saturday the [cutoff_time] must be before 15:00 (14:30 recommended)
     *                               //> so that Monday will be shown.
     *    "dropoff_delay"?: int, //This options allows the **Merchant** to set the number of days it takes them to pick, pack and hand in their parcels at the carrier when ordered before the cutoff time. The default value is 0.
     *    "deliverydays_window"?: int, //This options allows the Merchant to set the number of days into the future for which they want to show their consumers delivery options. For example, if set to 3 in their check-out, a consumer ordering on Monday will see possible delivery options for Tuesday, Wednesday and Thursday (provided there is no drop-off delay, it's before the cutoff time, and they go to PostNL on Mondays).
     *    "exclude_delivery_type"?: string, //Exclude shipments with a specific delivery type. This parameter can be used multiple times to exclude multiple delivery types.
     *    "exclude_parcel_lockers"?: mixed, //This option allows to filter out pickup locations that are parcel lockers.
     *    "latitude"?: int, //This provides the ability to search locations through the coordinates. If only latitude is provided without longitude, it will be ignored.
     *    "longitude"?: int, //This provides the ability to search locations through the coordinates. If only longitude is provided without latitude, it will be ignored.
     *    "package_type"?: string, //Package type to get the delivery options for. When omitted a
     *                             //carrier-specific default is used.
     *    "weight"?: int, //Weight in grams. Used to determine which package types and delivery
     *                    //options are available. Defaults to a carrier-specific weight when omitted.
     *    "delivery_type"?: string, //Delivery type to get the delivery options for.
     *    "same_day_delivery"?: mixed, //Include same day delivery options. Only available for carriers that
     *                                 //support it.
     *    "saturday_delivery"?: mixed, //Include Saturday delivery options. Only available for carriers that
     *                                 //support it.
     *    "include"?: string, //Semicolon-separated list of related resources to include in the response.
     *                        //Currently only `price` is supported.
     *    "shipment_id"?: int, //Identifier of an existing shipment to base the delivery options on.
     *                         //Used by carriers such as UPS and bol.com.
     * } $queryParameters
     * @param array $accept Accept content header application/json;version=2.0|application/json
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetDeliveryOptionsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetDeliveryOptionsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsV2|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsV1
     */
    public function getDeliveryOptions(array $queryParameters = [], array $accept = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\GetDeliveryOptions($queryParameters, $accept));
    }
    /**
     * Use this endpoint to receive a list of nearby drop off points, where shipments can be dropped off upon shipping. Results are ordered by distance from the provided postal code or coordinates.
     * @param array{
     *    "postal_code"?: string, //The postal code for which to fetch the resources.
     *    "number"?: string, //The street number for which to fetch the resources.
     *    "distance"?: int, //Provide the radius in kilometers for which you want to find drop off points. The default distance differs by carrier.
     *    "cc"?: string, //The country code for which to fetch the delivery options.
     *    "limit"?: int, //Limit the number of resources returned.
     *    "carrier_id"?: string,
     *    "shop_id"?: mixed,
     *    "reference"?: string, //Filter by `reference`.
     *    "location_name"?: string, //Filter by location name.
     *    "external_identifier"?: string,
     *    "city"?: string, //Only available for carriers Bpost and DPD. This can be used to narrow the search results for locations outside NL.
     *    "cut_off_time"?: string,
     *    "min_cut_off_time"?: string,
     *    "max_cut_off_time"?: string,
     *    "latitude"?: int, //This provides the ability to search locations through the coordinates. If only latitude is provided without longitude, it will be ignored.
     *    "longitude"?: int, //This provides the ability to search locations through the coordinates. If only longitude is provided without latitude, it will be ignored.
     *    "exclude_parcel_lockers"?: mixed, //This option allows to filter out pickup locations that are parcel lockers.
     * } $queryParameters
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetDropOffPointsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetDropOffPointsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDropOffPoints
     */
    public function getDropOffPoints(array $queryParameters = [], array $headerParameters = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\GetDropOffPoints($queryParameters, $headerParameters));
    }
    /**
     * @param array{
     *    "cc"?: string, //The country code for which to fetch the delivery options.
     *    "postal_code"?: string, //The postal code for which to fetch the resources.
     *    "number"?: string, //The street number for which to fetch the resources.
     *    "city"?: string, //Only available for carriers Bpost and DPD. This can be used to narrow the search results for locations outside NL.
     *    "street"?: string, //This can be used to narrow the search results for locations outside NL.
     *    "platform"?: string, //The platform where you want the data from.
     *    "shop_id"?: mixed,
     *    "carrier"?: string,
     *    "delivery_date"?: string, //The date on which the package has to be delivered.
     *    "delivery_time"?: string, //The time on which a package has to be delivered.
     *                              //> __Note__: This is only an indication of time the package will be
     *                              //>           delivered on the selected date.
     *    "cutoff_time"?: string, //This option allows the **Merchant** to indicate the latest cutoff time before which a consumer order will still be picked, packed and dispatched on the same/first set drop-off day, taking into account the drop-off delay. Default time is 15:30. For example, if cutoff time is 15:30, Monday is a delivery day and there's no delivery delay; all orders placed Monday before 15:30 will be dropped of at PostNL on that same Monday in time for the Monday collection.
     *    "dropoff_days"?: string, //This options allows the **Merchant** to set the days she normally goes to PostNL to hand in her parcels. By default Saturday and Sunday are excluded.
     *    "monday_delivery"?: mixed, //Monday delivery is only possible when the package is delivered before
     *                               //15.00 on Saturday at the designated PostNL locations.
     *                               //> __Note__: To activate Monday delivery, value 6 must be given with
     *                               //> [dropoff_days], value 1 must be given by [monday_delivery]. And on
     *                               //> Saturday the [cutoff_time] must be before 15:00 (14:30 recommended)
     *                               //> so that Monday will be shown.
     *    "dropoff_delay"?: int, //This options allows the **Merchant** to set the number of days it takes them to pick, pack and hand in their parcels at the carrier when ordered before the cutoff time. The default value is 0.
     *    "deliverydays_window"?: int, //This options allows the Merchant to set the number of days into the future for which they want to show their consumers delivery options. For example, if set to 3 in their check-out, a consumer ordering on Monday will see possible delivery options for Tuesday, Wednesday and Thursday (provided there is no drop-off delay, it's before the cutoff time, and they go to PostNL on Mondays).
     *    "exclude_delivery_type"?: string, //Exclude shipments with a specific delivery type. This parameter can be used multiple times to exclude multiple delivery types.
     *    "exclude_parcel_lockers"?: mixed, //This option allows to filter out pickup locations that are parcel lockers.
     *    "latitude"?: int, //This provides the ability to search locations through the coordinates. If only latitude is provided without longitude, it will be ignored.
     *    "longitude"?: int, //This provides the ability to search locations through the coordinates. If only longitude is provided without latitude, it will be ignored.
     * } $queryParameters
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetPickupLocationsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetPickupLocationsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocations
     */
    public function getPickupLocations(array $queryParameters = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\GetPickupLocations($queryParameters));
    }
    /**
     * This endpoint is often used by external parties to facilitate return shipments on a dedicated part of their website, mainly when offering reverse logistics e.g. repair services. It will allow the consumer to send packages to the merchant directly from the merchant's website.
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostUnrelatedReturnShipmentsNotFoundException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostUnrelatedReturnShipmentsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostUnrelatedReturnShipmentsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesDownloadUrl
     */
    public function postUnrelatedReturnShipments(array $headerParameters = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\PostUnrelatedReturnShipments($headerParameters));
    }
    /**
     * This operation returns a list of Shipments available to this User.
     * @param array{
     *    "barcode"?: string,
     *    "carrier_id"?: mixed,
     *    "created"?: string, //When set, only resources created after this date will be returned. Inclusive.
     *    "delayed"?: mixed, //Filter on whether the current event code means the shipment has been delayed.
     *    "delivered"?: bool,
     *    "dropoff_today"?: bool, //Use this parameter to only show Shipments that need to be dropped off today.
     *    "external_identifier"?: string,
     *    "filter_hidden_shops"?: bool,
     *    "hidden"?: bool,
     *    "include_secondary_shipments"?: mixed, //Whether to include secondary (multicollo child) Shipments in the results.
     *                                           //Pass `1` to include them; `0` (the default) excludes them.
     *    "link_consumer_portal"?: bool,
     *    "order"?: mixed, //Specify whether the results should be sorted in ascending or descending order.
     *    "package_type"?: mixed, //Filter by Package Type.
     *    "page"?: int, //Request a specific page of the results, used for paginated results.
     *    "q"?: string, //If this parameter is provided results will be filtered by the provided query or keyword.
     *    "reference_identifier"?: string, //Filter by `reference_identifier`, an optional arbitrary identifier to identify the Shipment.
     *    "region"?: string, //The region grouping of a Shipment. The concrete country value corresponds
     *                       //to the home country of the Shipment's platform; other country values
     *                       //yield no results for that platform.
     *    "shipment_type"?: mixed,
     *    "shop_id"?: mixed,
     *    "size"?: int, //Specify the number of resources returned per page, used for paginated results.
     *    "sort"?: string, //Sort Shipment results by a particular resource field.
     *    "status"?: mixed, //Filter by Shipment status. This filter will return only Shipments with the specified status.
     *    "transaction_status"?: string,
     * } $queryParameters
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipments
     */
    public function getShipments(array $queryParameters = [], array $headerParameters = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\GetShipments($queryParameters, $headerParameters));
    }
    /**
     * Partially update shipments. This operation can be used to update the
     * hidden, status, or delivered fields of one or more shipments.
     *
     * The status is only accepted as an actual state change when cancelling a
     * Trunkrs shipment: the shipment must still be in the Pending - Registered
     * status and within its cancellation window. For every other carrier the
     * status must match the current status of the shipment.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPatchShipmentsRequest $requestBody
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @param array $accept Accept content header application/*|application/problem+json|application/json
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PatchShipmentsUnsupportedMediaTypeException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PatchShipmentsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PatchShipmentsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return null
     */
    public function patchShipments(\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPatchShipmentsRequest $requestBody, array $headerParameters = [], array $accept = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\PatchShipments($requestBody, $headerParameters, $accept));
    }
    /**
     * Add shipments allows you to create standard and related return shipments.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequest|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostReturnShipmentsRequest|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequest $requestBody
     * @param array{
     *    "format"?: mixed, //The paper size of the PDF as specified in ISO216. Currently, A4 and A6 are supported. When A4 is chosen you
     *                      //can specify the label positions. When requesting the label for a shipment that contains a custom form, you can
     *                      //only request an A4 format.
     *    "positions"?: mixed, //The positions of the label on an A4 sheet. A position is identified by a digit from `1` to `4`. You can specify
     *                         //up to four positions separated by semicolons (`;`) or commas (`,`).
     *                         //
     *                         //__Note__: This parameter only works when you specify the `A4` __format__ and only applies to the first page
     *                         //when requesting multiple pages worth of labels. Subsequent pages use the default positioning `1;2;3;4`.
     *    "collect_date"?: string,
     *    "delivery_options_identifier"?: string,
     * } $queryParameters
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @param array $accept Accept content header application/json;charset=utf-8;version=1.2|application/json|application/vnd.shipment_label+json|application/*|application/problem+json
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostShipmentsUnsupportedMediaTypeException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostShipmentsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostShipmentsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPostShipmentsV12|null|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipmentLabels
     */
    public function postShipments($requestBody, array $queryParameters = [], array $headerParameters = [], array $accept = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\PostShipments($requestBody, $queryParameters, $headerParameters, $accept));
    }
    /**
     * This operation can be used to update certain fields of a shipment. The fields that can be modified depend on the status of the shipment.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequest $requestBody
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @param array $accept Accept content header application/json;charset=utf-8;version=1.2|application/json|application/*|application/problem+json
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PutShipmentUnsupportedMediaTypeException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PutShipmentClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PutShipmentServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPutShipmentsV12|null
     */
    public function putShipment($requestBody, array $headerParameters = [], array $accept = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\PutShipment($requestBody, $headerParameters, $accept));
    }
    /**
     * This operation can be used to delete shipments for which a label has not yet been created.
     * However, outside of housekeeping, this is not really necessary since Shipments not handed over to the distributor will not be billed by MyParcel; thus unused shipments which are not deleted have no financial consequences.
     * @param mixed $ids One or more shipment IDs. Separate multiple shipment IDs using `;`.
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @param array $accept Accept content header application/json|application/problem+json
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\DeleteShipmentsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\DeleteShipmentsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return null
     */
    public function deleteShipments($ids, array $headerParameters = [], array $accept = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\DeleteShipments($ids, $headerParameters, $accept));
    }
    /**
     * Get shipments by id. A maximum of 100 shipment IDs can be requested at once.
     * @param mixed $ids One or more shipment IDs. Separate multiple shipment IDs using `;`.
     * @param array{
     *    "link_consumer_portal"?: bool,
     * } $queryParameters
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsByIdClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsByIdServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipments
     */
    public function getShipmentsById($ids, array $queryParameters = [], array $headerParameters = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\GetShipmentsById($ids, $queryParameters, $headerParameters));
    }
    /**
     * **This endpoint is currently in beta and the API contract may change.**
     * List shipment capabilities of the carriers for the MyParcel platforms. This endpoint allows you to determine what delivery options, package types, and shipment options are available for specific carriers and destinations.
     *
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1 $requestBody
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @param array $accept Accept content header application/json;charset=utf-8;version=2.0|application/json;charset=utf-8|application/*|application/json
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostCapabilitiesClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostCapabilitiesServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV1
     */
    public function postCapabilities($requestBody, array $headerParameters = [], array $accept = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\PostCapabilities($requestBody, $headerParameters, $accept));
    }
    /**
     * **This endpoint is currently in beta and the API contract may change.**
     * List shipment capabilities of the carriers for the MyParcel platforms. This endpoint allows you to determine the complete set of delivery options, package types, and shipment options available to a specific account.
     *
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostContractDefinitionsRequestV2|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostContractDefinitionsRequestV1 $requestBody
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @param array $accept Accept content header application/json;charset=utf-8;version=2.0|application/json;charset=utf-8|application/*|application/json
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostCapabilitiesContractDefinitionsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostCapabilitiesContractDefinitionsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesContractDefinitionsV2|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesContractDefinitionsV1
     */
    public function postCapabilitiesContractDefinitions($requestBody, array $headerParameters = [], array $accept = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\PostCapabilitiesContractDefinitions($requestBody, $headerParameters, $accept));
    }
    /**
     * This endpoint allows you to determine the rates for a shipment configuration.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV2|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1 $requestBody
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @param array $accept Accept content header application/json;charset=utf-8;version=2.0|application/json;charset=utf-8|application/*|application/json
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostRatesClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostRatesServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponseRateV2|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesResponsesRatesV1|null
     */
    public function postRates($requestBody, array $headerParameters = [], array $accept = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\PostRates($requestBody, $headerParameters, $accept));
    }
    /**
     * #### Get Shipment labels
     * You can specify label format and positions of labels on the first page with the
     * __format__ and __positions__ query parameters. The __positions__ query only works when you specify the A4 format
     * and is only applied on the first page with labels. Accounts with __Post-payment__ payment methods can fetch
     * multiple labels in one call. For accounts with __Pre-payment__ payment method an `HTTP 402 Payment Required`
     * with a PaymentInstructions object is returned if the label has not been paid for yet.
     *
     * ##### Multi-collo shipments
     * When a label for a multi collo shipment is requested, labels for all shipments parts of the multi collo shipment
     * will be generated. Each shipment within a multi collo shipment MUST be labeled with a specific label containing
     * a unique barcode.
     *
     * @param mixed $ids One or more shipment IDs. Separate multiple shipment IDs using `;`.
     * @param array{
     *    "format"?: mixed, //The paper size of the PDF as specified in ISO216. Currently, A4 and A6 are supported. When A4 is chosen you
     *                      //can specify the label positions. When requesting the label for a shipment that contains a custom form, you can
     *                      //only request an A4 format.
     *    "positions"?: mixed, //The positions of the label on an A4 sheet. A position is identified by a digit from `1` to `4`. You can specify
     *                         //up to four positions separated by semicolons (`;`) or commas (`,`).
     *                         //
     *                         //__Note__: This parameter only works when you specify the `A4` __format__ and only applies to the first page
     *                         //when requesting multiple pages worth of labels. Subsequent pages use the default positioning `1;2;3;4`.
     *    "collect_date"?: string,
     *    "delivery_options_identifier"?: string,
     * } $queryParameters
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @param array $accept Accept content header application/*|application/json|application/vnd.shipment_label_link+json|application/vnd.shipment_label_link+json+print|application/json+print|application/pdf+print|application/vnd.shipment_label+json+print|application/vnd.shipment_label+pdf+print|application/vnd.shipment_label+zpl+print|application/vnd.zpl+print|*\/*
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsLabelsPaymentRequiredException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsLabelsNotFoundException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsLabelsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsLabelsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return null|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesDownloadLink
     */
    public function getShipmentsLabels($ids, array $queryParameters = [], array $headerParameters = [], array $accept = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\GetShipmentsLabels($ids, $queryParameters, $headerParameters, $accept));
    }
    /**
     * Get detailed Track & Trace information for one or more shipments.
     * @param array{
     *    "barcode"?: string,
     *    "country_code"?: string,
     *    "external_identifier"?: string,
     *    "extra_info"?: string, //Enables extra info in the response that is not included by default for performance reasons.
     *    "postal_code"?: string, //The postal code for which to fetch the resources.
     *    "sort"?: string, //Sort order. Defaults to `desc`.
     * } $queryParameters
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetTrackTracesNotFoundException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetTrackTracesClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetTrackTracesServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesTracktraces
     */
    public function getTrackTraces(array $queryParameters = [], array $headerParameters = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\GetTrackTraces($queryParameters, $headerParameters));
    }
    /**
     * Get detailed Track & Trace information for one or more shipments.
     * @param mixed $ids One or more shipment IDs. Separate multiple shipment IDs using `;`.
     * @param array{
     *    "sort"?: string, //Sort order. Defaults to `desc`.
     *    "extra_info"?: string, //Enables extra info in the response that is not included by default for performance reasons.
     * } $queryParameters
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetTrackTracesByIdsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetTrackTracesByIdsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesTracktraces
     */
    public function getTrackTracesByIds($ids, array $queryParameters = [], array $headerParameters = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\GetTrackTracesByIds($ids, $queryParameters, $headerParameters));
    }
    /**
     * Retrieve active webhook subscriptions for this account. Returns an array of Subscription objects.
     * @param array{
     *    "hook"?: string, //Filter by webhook event type.
     * } $queryParameters
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @param array $accept Accept content header application/json;charset=utf-8;version=1.1|application/json;charset=utf-8|application/json
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetWebhookSubscriptionsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetWebhookSubscriptionsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksResponsesWebhookSubscriptionsV11|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksResponsesWebhookSubscriptionsV10
     */
    public function getWebhookSubscriptions(array $queryParameters = [], array $headerParameters = [], array $accept = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\GetWebhookSubscriptions($queryParameters, $headerParameters, $accept));
    }
    /**
     * Create webhook subscriptions for event notifications.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksPostWebhookSubscriptionsRequestV11|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksPostWebhookSubscriptionsRequestV10 $requestBody
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostWebhookSubscriptionsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostWebhookSubscriptionsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksResponsesPostWebhookSubscriptions
     */
    public function postWebhookSubscriptions($requestBody, array $headerParameters = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\PostWebhookSubscriptions($requestBody, $headerParameters));
    }
    /**
     * Delete webhook subscriptions to stop receiving notifications about events.
     * @param mixed $ids One or more webhook subscription IDs. Separate multiple IDs using `;`.
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\DeleteWebhookSubscriptionsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\DeleteWebhookSubscriptionsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return null
     */
    public function deleteWebhookSubscriptions($ids, array $headerParameters = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\DeleteWebhookSubscriptions($ids, $headerParameters));
    }
    /**
     * Retrieve specific webhook subscriptions by id. You can specify multiple subscription ids by semicolon separating them on the URI. Returns an array of webhook subscriptions.
     * @param mixed $ids One or more webhook subscription IDs. Separate multiple IDs using `;`.
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @param array $accept Accept content header application/json;charset=utf-8;version=1.1|application/json;charset=utf-8|application/json
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetWebhookSubscriptionsByIdClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetWebhookSubscriptionsByIdServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksResponsesWebhookSubscriptionsV11|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksResponsesWebhookSubscriptionsV10
     */
    public function getWebhookSubscriptionsById($ids, array $headerParameters = [], array $accept = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\GetWebhookSubscriptionsById($ids, $headerParameters, $accept));
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
        $normalizers = [new \Symfony\Component\Serializer\Normalizer\ArrayDenormalizer(), new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\JaneObjectNormalizer()];
        if (count($additionalNormalizers) > 0) {
            $normalizers = array_merge($normalizers, $additionalNormalizers);
        }
        $serializer = new \Symfony\Component\Serializer\Serializer($normalizers, [new \Symfony\Component\Serializer\Encoder\JsonEncoder(new \Symfony\Component\Serializer\Encoder\JsonEncode(), new \Symfony\Component\Serializer\Encoder\JsonDecode(['json_decode_associative' => true])), new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\FormEncoder()]);
        return new static($httpClient, $serializer);
    }
}