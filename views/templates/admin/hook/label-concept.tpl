<input id="deliveryDate" name="deliveryDate" type="hidden" value="{$deliveryOptions.date}">
<div class="form-group">
  <label class="col-lg-3 control-label">{l s='Package type' mod='myparcelbe'}</label>
  <div class="col-lg-3">
    <select name="packageType" class="form-control custom-select">
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
<div class="form-group">
  <label class="col-lg-3 control-label">{l s='Package format' mod='myparcelbe'}</label>
  <div class="col-lg-3">
    <select name="packageFormat" class="form-control custom-select">
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
<div class="form-group">
  <label class="col-lg-3 control-label">{l s='Number of labels' mod='myparcelbe'}</label>
  <div class="col-lg-2">
    <input class="form-control" id="label_amount" name="label_amount" type="number" min="1" max="10" value="1">
  </div>
</div>
<div class="form-group label-delivery-options">
  <div class="col-lg-9 col-lg-offset-3">
    {if $carrierSettings.delivery.onlyRecipient}
      <p class="checkbox">
        <label class="control-label text-left" for="label_recipient_only">
          {l s='Recipient only' mod='myparcelbe'}
          <input
                  name="onlyRecipient"
                  type="checkbox"
                  id="label_recipient_only"
                  value="1"
                  {if !empty($deliveryOptions.shipmentOptions.only_recipient) || !empty($labelOptions.only_to_recipient)}checked{/if}
          />
        </label>
      </p>
    {/if}
    {if $carrierSettings.delivery.signatureRequired}
      <p class="checkbox">
        <label class="control-label text-left" for="label_require_signature">
          {l s='Requires a signature' mod='myparcelbe'}
          <input
                  name="signatureRequired"
                  type="checkbox"
                  id="label_require_signature"
                  value="1"
                  {if !empty($deliveryOptions.shipmentOptions.signature) || !empty($labelOptions.signature)}checked{/if}
          />
        </label>
      </p>
    {/if}
    {if $carrierSettings.delivery.returnUndelivered}
      <p class="checkbox">
        <label class="control-label text-left" for="label_return">
          {l s='Return when undeliverable' mod='myparcelbe'}
          <input
                  name="returnUndelivered"
                  type="checkbox"
                  id="label_return"
                  value="1"
                  {if !empty($deliveryOptions.shipmentOptions.return) || !empty($labelOptions.return_undelivered)}checked{/if}
          />
        </label>
      </p>
    {/if}
    {if $carrierSettings.delivery.ageCheck}
      <p class="checkbox">
        <label class="control-label text-left" for="label_age_check">
          {l s='Age check 18+' mod='myparcelbe'}
          <input
                  name="ageCheck"
                  type="checkbox"
                  id="label_age_check"
                  value="1"
                  {if !empty($deliveryOptions.shipmentOptions.age_check) || !empty($labelOptions.age_check)}checked{/if}
          />
        </label>
      </p>
    {/if}
    {if $carrierSettings.delivery.insurance}
      <p class="checkbox">
        <label class="control-label text-left" for="label_insurance">
          {l s='Insurance' mod='myparcelbe'}
          <input
                  name="insurance"
                  type="checkbox"
                  id="label_insurance"
                  value="1"
                  {if !empty($deliveryOptions.shipmentOptions.insurance) || !empty($labelOptions.insurance)}checked{/if}
          />
        </label>
      </p>
      <div class="insurance-values">
        <label class="control-label" for="insurance_amount_100">
          <input
                  name="insuranceAmount"
                  type="radio"
                  id="insurance_amount_100"
                  value="amount100"
                  {if !empty($deliveryOptions.shipmentOptions.insurance.amount)
                  && $deliveryOptions.shipmentOptions.insurance.amount eq 10000
                  || (empty($deliveryOptions.shipmentOptions.insurance) && !empty($labelOptions.insurance))}checked{/if}
          />
          {l s='Up to € 100' mod='myparcelbe'}
        </label>
        <label class="control-label" for="insurance_amount_250">
          <input
                  name="insuranceAmount"
                  type="radio"
                  id="insurance_amount_250"
                  value="amount250"
                  {if !empty($deliveryOptions.shipmentOptions.insurance.amount)
                  && $deliveryOptions.shipmentOptions.insurance.amount eq 25000}checked{/if}
          />
          {l s='Up to € 250' mod='myparcelbe'}
        </label>
        <label class="control-label" for="insurance_amount_500">
          <input
                  name="insuranceAmount"
                  type="radio"
                  id="insurance_amount_500"
                  value="amount500"
                  {if !empty($deliveryOptions.shipmentOptions.insurance.amount)
                  && $deliveryOptions.shipmentOptions.insurance.amount eq 50000}checked{/if}
          />
          {l s='Up to € 500' mod='myparcelbe'}
        </label>
          {if !$isBE}
            <label class="control-label insurance-amount-custom" for="insurance_amount_custom">
              <input
                      name="insuranceAmount"
                      type="radio"
                      id="insurance_amount_custom"
                      value="-1"
                      {if !empty($deliveryOptions.shipmentOptions.insurance.amount)
                      && !in_array($deliveryOptions.shipmentOptions.insurance.amount, ['10000', '25000', '50000'])
                      && $deliveryOptions.shipmentOptions.insurance.amount|intval >= 100
                      }checked{/if}
              />
              <span>{l s='More than € 500' mod='myparcelbe'}</span>
              <div class="input-group">
                <span class="input-group-addon">{$currencySign}</span>
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
              </div>
            </label>
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
