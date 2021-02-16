<input id="deliveryDate" name="deliveryDate" type="hidden" value="{$deliveryOptions.date}">
<div class="form-group row">
  <label class="col-sm-4 col-form-label" for="packageTypeSelect">{l s='Package type' mod='myparcelbe'}</label>
  <div class="col-sm-8">
    <select name="packageType" class="form-control custom-select" id="packageTypeSelect">
      {if !empty($carrierSettings.delivery.packageType[1])}
        <option value="1"{if !empty($labelOptions.package_type) && $labelOptions.package_type eq 1} selected{/if}>{l s='Parcel' mod='myparcelbe'}</option>
      {/if}
      {if !empty($carrierSettings.delivery.packageType[2])}
        <option value="2"{if !empty($labelOptions.package_type) && $labelOptions.package_type eq 2} selected{/if}>{l s='Mailbox package' mod='myparcelbe'}</option>
      {/if}
      {if !empty($carrierSettings.delivery.packageType[3])}
        <option value="3"{if !empty($labelOptions.package_type) && $labelOptions.package_type eq 3} selected{/if}>{l s='Letter' mod='myparcelbe'}</option>
      {/if}
      {if !empty($carrierSettings.delivery.packageType[4])}
        <option value="4"{if !empty($labelOptions.package_type) && $labelOptions.package_type eq 4} selected{/if}>{l s='Digital stamp' mod='myparcelbe'}</option>
      {/if}
    </select>
  </div>
</div>
<div class="form-group row">
  <label class="col-sm-4 col-form-label" for="packageFormatSelect">{l s='Package format' mod='myparcelbe'}</label>
  <div class="col-sm-8">
    <select name="packageFormat" class="form-control custom-select" id="packageFormatSelect">
      {if !empty($carrierSettings.delivery.packageFormat[1])}
        <option value="1"{if !empty($labelOptions.package_format) && $labelOptions.package_format eq 1} selected{/if}>{l s='Normal' mod='myparcelbe'}</option>
      {/if}
      {if !empty($carrierSettings.delivery.packageFormat[2])}
        <option value="2"{if !empty($labelOptions.package_format) && $labelOptions.package_format eq 2} selected{/if}>{l s='Large' mod='myparcelbe'}</option>
      {/if}
      {if !empty($carrierSettings.delivery.packageFormat[3])}
        <option value="3"{if !empty($labelOptions.package_format) && $labelOptions.package_format eq 3} selected{/if}>{l s='Automatic' mod='myparcelbe'}</option>
      {/if}
    </select>
  </div>
</div>
<div class="form-group row">
  <label class="col-sm-4 col-form-label" for="label_amount">{l s='Number of labels' mod='myparcelbe'}</label>
  <div class="col-lg-2">
    <input class="form-control" id="label_amount" name="label_amount" type="number" min="1" max="10" value="1">
  </div>
</div>
<div class="form-group row label-delivery-options">
  <div class="col-sm-8 offset-sm-4">
    {if $carrierSettings.delivery.onlyRecipient}
      <div class="form-check">
        <input
                class="form-check-input"
                name="onlyRecipient"
                type="checkbox"
                id="label_recipient_only"
                value="1"
                {if !empty($deliveryOptions.shipmentOptions.only_recipient) || !empty($labelOptions.only_to_recipient)}checked{/if}
        />
        <label class="form-check-label" for="label_recipient_only">
          {l s='Recipient only' mod='myparcelbe'}
        </label>
      </div>
    {/if}
    {if $carrierSettings.delivery.signatureRequired}
      <div class="form-check">
        <input
                class="form-check-input"
                name="signatureRequired"
                type="checkbox"
                id="label_require_signature"
                value="1"
                {if !empty($deliveryOptions.shipmentOptions.signature) || !empty($labelOptions.signature)}checked{/if}
        />
        <label class="form-check-label" for="label_require_signature">
          {l s='Requires a signature' mod='myparcelbe'}
        </label>
      </div>
    {/if}
    {if $carrierSettings.delivery.returnUndelivered}
      <div class="form-check">
        <input
                class="form-check-input"
                name="returnUndelivered"
                type="checkbox"
                id="label_return"
                value="1"
                {if !empty($deliveryOptions.shipmentOptions.return) || !empty($labelOptions.return_undelivered)}checked{/if}
        />
        <label class="form-check-label" for="label_return">
          {l s='Return when undeliverable' mod='myparcelbe'}
        </label>
      </div>
    {/if}
    {if $carrierSettings.delivery.ageCheck}
      <div class="form-check">
        <input
                class="form-check-input"
                name="ageCheck"
                type="checkbox"
                id="label_age_check"
                value="1"
                {if !empty($deliveryOptions.shipmentOptions.age_check) || !empty($labelOptions.age_check)}checked{/if}
        />
        <label class="form-check-label" for="label_age_check">
          {l s='Age check 18+' mod='myparcelbe'}
        </label>
      </div>
    {/if}
    {if $carrierSettings.delivery.insurance}
      <div class="form-check">
        <input
                class="form-check-input"
                name="insurance"
                type="checkbox"
                id="label_insurance"
                value="1"
                {if !empty($deliveryOptions.shipmentOptions.insurance) || !empty($labelOptions.insurance)}checked{/if}
        />
        <label class="form-check-label" for="label_insurance">
          {l s='Insurance' mod='myparcelbe'}
        </label>
      </div>
      <div class="insurance-values">
        <div class="form-check mt-1">
          <input
                  class="form-check-input"
                  name="insuranceAmount"
                  type="radio"
                  id="insurance_amount_100"
                  value="amount100"
                  {if !empty($deliveryOptions.shipmentOptions.insurance.amount)
                  && $deliveryOptions.shipmentOptions.insurance.amount eq 10000
                  || (empty($deliveryOptions.shipmentOptions.insurance) && !empty($labelOptions.insurance))}checked{/if}
          />
          <label class="form-check-label" for="insurance_amount_100">
            {l s='Up to € 100' mod='myparcelbe'}
          </label>
        </div>
        <div class="form-check mt-1">
          <input
                  class="form-check-input"
                  name="insuranceAmount"
                  type="radio"
                  id="insurance_amount_250"
                  value="amount250"
                  {if !empty($deliveryOptions.shipmentOptions.insurance.amount)
                  && $deliveryOptions.shipmentOptions.insurance.amount eq 25000}checked{/if}
          />
          <label class="form-check-label" for="insurance_amount_250">
            {l s='Up to € 250' mod='myparcelbe'}
          </label>
        </div>
        <div class="form-check mt-1">
          <input
                  class="form-check-input"
                  name="insuranceAmount"
                  type="radio"
                  id="insurance_amount_500"
                  value="amount500"
                  {if !empty($deliveryOptions.shipmentOptions.insurance.amount)
                  && $deliveryOptions.shipmentOptions.insurance.amount eq 50000}checked{/if}
          />
          <label class="form-check-label" for="insurance_amount_500">
            {l s='Up to € 500' mod='myparcelbe'}
          </label>
        </div>
        {if !$isBE}
          <div class="form-check mt-1 d-flex align-items-center insurance-amount-custom">
            <input
                    class="form-check-input mt-0"
                    name="insuranceAmount"
                    type="radio"
                    id="insurance_amount_custom"
                    value="-1"
                    {if !empty($deliveryOptions.shipmentOptions.insurance.amount)
                    && !in_array($deliveryOptions.shipmentOptions.insurance.amount, ['10000', '25000', '50000'])
                    && $deliveryOptions.shipmentOptions.insurance.amount|intval >= 100
                    }checked{/if}
            />
            <label class="form-check-label" for="insurance_amount_custom">
              <span class="d-flex pl-0 align-items-center">
                <span class="mr-2">{l s='More than € 500' mod='myparcelbe'}</span>
                <span class="input-group">
                  <span class="input-group-prepend">
                    <span class="input-group-text">{$currencySign}</span>
                  </span>
                  <input
                          class="form-control fixed-width-sm"
                          type="text"
                          id="insurance-amount-custom-value"
                          name="insurance-amount-custom-value"
                          value="{if !empty($deliveryOptions.shipmentOptions.insurance.amount)
                          && !in_array($deliveryOptions.shipmentOptions.insurance.amount, ['10000', '25000', '50000'])
                          && $deliveryOptions.shipmentOptions.insurance.amount|intval >= 100
                          }{$deliveryOptions.shipmentOptions.insurance.amount / 100}{else}1000{/if}"
                  />
                </span>
              </span>
            </label>
          </div>
        {/if}
      </div>
    {/if}
  </div>
</div>
{if $date_warning_display}
  <div class="alert alert-warning">
      {l s='The delivery timeframe has been moved to a new date.' mod='myparcelbe'}
  </div>
{/if}
