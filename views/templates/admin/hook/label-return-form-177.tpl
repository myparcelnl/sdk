<form action="{$labelUrl}" method="post" id="return_label_form">
  <input name="id_order_label" type="hidden" value="">
  <div class="form-group row">
    <label class="col-lg-3 control-label">{l s='Customer Name' mod='myparcelbe'}<sup>*</sup></label>
    <div class="col-lg-9">
      <input class="form-control" name="label_name" type="text" value="{$customerName}" />
    </div>
  </div>
  <div class="form-group row">
    <label class="col-lg-3 control-label">{l s='Email' mod='myparcelbe'}<sup>*</sup></label>
    <div class="col-lg-9">
      <input class="form-control" name="label_email" type="text" value="{$customerEmail}" />
    </div>
  </div>
  <div class="form-group row custom-label-return-description">
    <label class="col-lg-3 control-label">{l s='Custom Label' mod='myparcelbe'}</label>
    <div class="col-lg-9">
      <input class="form-control" name="label_description" type="text" value="" />
    </div>
  </div>
  <div class="form-group row">
    <label class="col-lg-3 control-label">{l s='Package type' mod='myparcelbe'}<sup>*</sup></label>
    <div class="col-lg-6">
      <select name="packageType" class="form-control custom-select">
        {if !empty($carrierSettings.return.packageType[1])}
          <option value="1">{l s='Parcel' mod='myparcelbe'}</option>
        {/if}
        {if !empty($carrierSettings.return.packageType[2])}
          <option value="2">{l s='Mailbox package' mod='myparcelbe'}</option>
        {/if}
        {if !empty($carrierSettings.return.packageType[3])}
          <option value="3">{l s='Letter' mod='myparcelbe'}</option>
        {/if}
        {if !empty($carrierSettings.return.packageType[4])}
          <option value="4">{l s='Digital stamp' mod='myparcelbe'}</option>
        {/if}
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-lg-3 control-label">{l s='Package format' mod='myparcelbe'}</label>
    <div class="col-lg-6">
      <select name="packageFormat" class="form-control custom-select">
        {if !empty($carrierSettings.return.packageFormat[1])}
          <option value="1">{l s='Normal' mod='myparcelbe'}</option>
        {/if}
        {if !empty($carrierSettings.return.packageFormat[2])}
          <option value="2">{l s='Large' mod='myparcelbe'}</option>
        {/if}
        {if !empty($carrierSettings.return.packageFormat[3])}
          <option value="3">{l s='Automatic' mod='myparcelbe'}</option>
        {/if}
      </select>
    </div>
  </div>
  <div class="form-group row label-delivery-options">
    <div class="col-lg-9 col-lg-offset-3">
      {if $carrierSettings.return.onlyRecipient}
        <p class="checkbox">
          <label class="control-label text-left" for="label_return_recipient_only">
            {l s='Recipient only' mod='myparcelbe'}
            <input name="onlyRecipient" type="checkbox" id="label_return_recipient_only" value="1" />
          </label>
        </p>
      {/if}
      {if $carrierSettings.return.signatureRequired}
        <p class="checkbox">
          <label class="control-label text-left" for="label_return_require_signature">
            {l s='Requires a signature' mod='myparcelbe'}
            <input name="signatureRequired" type="checkbox" id="label_return_require_signature" value="1" />
          </label>
        </p>
      {/if}
      {if $carrierSettings.return.returnUndelivered}
        <p class="checkbox">
          <label class="control-label text-left" for="label_return">
            {l s='Return when undeliverable' mod='myparcelbe'}
            <input name="returnUndelivered" type="checkbox" id="label_return_return" value="1" />
          </label>
        </p>
      {/if}
      {if $carrierSettings.return.ageCheck}
        <p class="checkbox">
          <label class="control-label text-left" for="label_return_age_check">
            {l s='Age check 18+' mod='myparcelbe'}
            <input name="ageCheck" type="checkbox" id="label_return_age_check" value="1" />
          </label>
        </p>
      {/if}
      {if $carrierSettings.return.insurance}
        <p class="checkbox">
          <label class="control-label text-left" for="label_return_insurance">
            {l s='Insurance' mod='myparcelbe'}
            <input name="insurance" type="checkbox" id="label_return_insurance" value="1" />
          </label>
        </p>
        <div class="insurance-values">
          <label class="control-label" for="return_insurance_amount_100">
            <input name="returnInsuranceAmount" type="radio" id="return_insurance_amount_100" value="amount100" />
            {l s='Up to € 100' mod='myparcelbe'}
          </label>
          <label class="control-label" for="return_insurance_amount_250">
            <input name="returnInsuranceAmount" type="radio" id="return_insurance_amount_250" value="amount250" />
            {l s='Up to € 250' mod='myparcelbe'}
          </label>
          <label class="control-label" for="return_insurance_amount_500">
            <input name="returnInsuranceAmount" type="radio" id="return_insurance_amount_500" value="amount500" />
            {l s='Up to € 500' mod='myparcelbe'}
          </label>
          {if !$isBE}
            <label class="control-label return-insurance-amount-custom" for="return_insurance_amount_custom">
              <input name="returnInsuranceAmount" type="radio" id="return_insurance_amount_custom" value="-1" />
              <span>{l s='More than € 500' mod='myparcelbe'}</span>
              <span class="input-group">
                <span class="input-group-addon">{$currencySign}</span>
                <input
                        class="form-control fixed-width-sm"
                        type="text"
                        id="return-insurance-amount-custom-value"
                        name="insurance-amount-custom-value"
                        value="1000"
                />
              </span>
            </label>
          {/if}
        </div>
      {/if}
    </div>
  </div>
</form>
