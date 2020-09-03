<div id="myparcel-order-panel" class="panel" data-url="{$labelUrl}">
  <div class="panel-heading">
    <img src="{$modulePathUri}views/images/myparcelnl-grayscale.png" alt="" /> {l s='MyParcel' mod='myparcelbe'}
  </div>
  <div class="row">
    <div class="col-lg-6">
      <div class="panel form-horizontal">
        <div class="panel-heading">
          <i class="icon-file-text"></i> {l s='Concept' mod='myparcelbe'}
          <button class="badge badge-concept-date">
            <div class="concept-date">
              <span class="delivery-options-span">
                {dateFormat date=$deliveryOptions.date full=false}
                <i class="icon-pencil"></i>
              </span>
            </div>
          </button>
        </div>
        <div class="concept-label-wrapper form-wrapper">
          <input id="deliveryDate" name="deliveryDate" type="hidden" value="{$deliveryOptions.date}">
          <div class="form-group">
            <label class="col-lg-3 control-label">{l s='Package type' mod='myparcelbe'}</label>
            <div class="col-lg-3">
              <select name="packageType" class="form-control custom-select">
                {if !empty($carrierSettings.delivery.packageType[1])}
                  <option value="1">{l s='Parcel' mod='myparcelbe'}</option>
                {/if}
                {if !empty($carrierSettings.delivery.packageType[2])}
                  <option value="2">{l s='Mailbox package' mod='myparcelbe'}</option>
                {/if}
                {if !empty($carrierSettings.delivery.packageType[3])}
                  <option value="3">{l s='Letter' mod='myparcelbe'}</option>
                {/if}
                {if !empty($carrierSettings.delivery.packageType[4])}
                  <option value="4">{l s='Digital stamp' mod='myparcelbe'}</option>
                {/if}
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">{l s='Package format' mod='myparcelbe'}</label>
            <div class="col-lg-3">
              <select name="packageFormat" class="form-control custom-select">
                {if !empty($carrierSettings.delivery.packageFormat[1])}
                  <option value="1">{l s='Normal' mod='myparcelbe'}</option>
                {/if}
                {if !empty($carrierSettings.delivery.packageFormat[2])}
                  <option value="2">{l s='Large' mod='myparcelbe'}</option>
                {/if}
                {if !empty($carrierSettings.delivery.packageFormat[3])}
                  <option value="3">{l s='Automatic' mod='myparcelbe'}</option>
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
          <div class="form-group">
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
                            {if !empty($deliveryOptions.shipmentOptions.only_recipient)}checked{/if}
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
                            {if !empty($deliveryOptions.shipmentOptions.signature)}checked{/if}
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
                            {if !empty($deliveryOptions.shipmentOptions.return)}checked{/if}
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
                            {if !empty($deliveryOptions.shipmentOptions.age_check)}checked{/if}
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
                            {if !empty($deliveryOptions.shipmentOptions.insurance)}checked{/if}
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
                            && $deliveryOptions.shipmentOptions.insurance.amount eq 10000}checked{/if}
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
                              id="insurance_amount_more"
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
        </div>
        <div class="concept-label-address">
          <div class="well">
            <button class="btn btn-default pull-right" type="button">
              <i class="icon-pencil"></i>
                {l s='Edit' mod='myparcelbe'}
            </button>
              {$delivery_address_formatted}
          </div>
        </div>
        <div class="panel-footer-wrapper">
          <button type="submit" name="submitCreateConcept" class="btn btn-default" id="submitCreateConcept">
            <i class="icon-save"></i> {l s='Save' mod='myparcelbe'}
          </button>
          <div class="btn-group">
            <button class="btn btn-default" type="button" name="submitCreateLabel" id="submitCreateLabel">
              <i class="icon-plus"></i> {l s='New shipment' mod='myparcelbe'}
            </button>
            <button
                    class="btn btn-default"
                    type="button"
                    name="submitCreateLabelPrint"
                    id="submitCreateLabelPrint"
                    data-toggle="modal"
                    data-target="#printLabelModal"
            >
              <i class="icon-plus"></i> <i class="icon-print"></i> {l s='New shipment & print' mod='myparcelbe'}
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="panel">
        <div class="panel-heading">
          <i class="icon-truck"></i> {l s='Shipments' mod='myparcelbe'}
        </div>
        <form class="shipment-labels-wrapper" action="{$labelAction}" method="post">
          <div class="table-responsive">
            <table class="table">
              <thead>
              <tr>
                <th><span class="title_box text-center">--</span></th>
                <th><span class="title_box">{l s='Track & Trace' mod='myparcelbe'}</span></th>
                <th><span class="title_box">{l s='Status' mod='myparcelbe'}</span></th>
                <th><span class="title_box">{l s='Last update' mod='myparcelbe'}</span></th>
                <th></th>
              </tr>
              </thead>
              <tbody>
              <tr{if !empty($labelList)} class="hidden"{/if}>
                <td class="list-empty hidden-print" colspan="5">
                  <div class="list-empty-msg">
                    <i class="icon-exclamation-triangle"></i>
                    <div>{l s='There are no shipments' mod='myparcelbe'}</div>
                  </div>
                </td>
              </tr>
              {if !empty($labelList)}
                {foreach $labelList as $label}
                  <tr>
                    <td><input type="checkbox" name="labelBox[]" class="noborder" value="{$label.id_order_label}"></td>
                    <td>
                      <a href="{$label.track_link}" target="_blank" rel="noopener noreferrer">{$label.barcode}</a>
                    </td>
                    <td>{$label.status}</td>
                    <td>{dateFormat date=$label.date_upd full=true}</td>
                    <td class="order-label-action text-right">
                      <div class="btn-group" id="btn_group_action">
                        <button type="button" class="btn btn-default order-label-action-print">
                          <i class="icon-print"></i> {l s='Print' mod='myparcelbe'}
                        </button>
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                          <li>
                            <a href="#" class="order-label-action-refresh">
                              <i class="icon-refresh"></i> {l s='Refresh' mod='myparcelbe'}
                            </a>
                          </li>
                          <li>
                            <a href="#" class="order-label-action-return{if !empty($label.return_disabled)} disabled{/if}">
                              <i class="icon-reply"></i> {l s='Create return label' mod='myparcelbe'}
                            </a>
                          </li>
                          <li class="divider"></li>
                          <li>
                            <a href="#" class="delete_product_line">
                              <i class="icon-trash"></i> {l s='Delete' d='Admin.Actions'}
                            </a>
                          </li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                {/foreach}
              {/if}
              </tbody>
            </table>
          </div>
          <div class="btn-group bulk-actions dropup">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"{if empty($labelList)} disabled{/if}>
                {l s='Bulk actions' d='Admin.Global'} <span class="caret"></span>
            </button>
            <ul class="dropdown-menu label-dropdown">
              <li>
                <a href="#" onclick="javascript:checkDelBoxes($(this).closest('.shipment-labels-wrapper').get(0), 'labelBox[]', true);return false;">
                  <i class="icon-check-sign"></i>&nbsp;{l s='Select all'}
                </a>
              </li>
              <li>
                <a href="#" onclick="javascript:checkDelBoxes($(this).closest('.shipment-labels-wrapper').get(0), 'labelBox[]', false);return false;">
                  <i class="icon-check-empty"></i>&nbsp;{l s='Unselect all'}
                </a>
              </li>
              <li class="divider"></li>
                {foreach $bulk_actions as $key => $params}
                  <li{if $params.text == 'divider'} class="divider"{/if}>
                      {if $params.text != 'divider'}
                        <a
                                href="#"
                                onclick="{if isset($params.confirm)}if (confirm('{$params.confirm}')){/if}sendBulkAction($(this).closest('.shipment-labels-wrapper').get(0), 'submitBulk{$key}');"
                        >
                            {if isset($params.icon)}<i class="{$params.icon}"></i>{/if}&nbsp;{$params.text}
                        </a>
                      {/if}
                  </li>
                {/foreach}
            </ul>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="printLabelModal" tabindex="-1" role="dialog" aria-labelledby="modalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLongTitle">{l s='Print' mod='myparcelbe'}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{$labelUrl}" method="post" id="print_label_form">
          <input type="hidden" name="action" value="printOrderLabel">
          <div class="myparcel-radio-wrapper">
            <div class="myparcel-radio-container">
              <input
                      id="a4"
                      type="radio"
                      value="a4"
                      name="format"
                      class="myparcel-radio page-format-radio page-format-a4"
                      {if $labelConfiguration.MY_PARCEL_LABEL_SIZE eq 'a4'}checked="checked"{/if}
              >
              <label for="a4">{l s='A4' mod='myparcelbe'}</label>
            </div>
            <div class="myparcel-radio-container">
              <input
                      id="a6"
                      type="radio"
                      value="a6"
                      name="format"
                      class="myparcel-radio page-format-radio page-format-a6"
                      {if $labelConfiguration.MY_PARCEL_LABEL_SIZE eq 'a6'}checked="checked"{/if}
              >
              <label for="a6">{l s='A6' mod='myparcelbe'}</label>
            </div>
          </div>
          <br>
          <div class="positions-block">
            <input
                    id="top-left"
                    type="checkbox"
                    value="1"
                    name="position[]"
                    {if $labelConfiguration.MY_PARCEL_LABEL_POSITION eq 1}checked="checked"{/if}
            >
            <label for="top-left">{l s='Top-left' mod='myparcelbe'}</label>
            <br>
            <input
                    id="top-right"
                    type="checkbox"
                    value="2"
                    name="position[]"
                    {if $labelConfiguration.MY_PARCEL_LABEL_POSITION eq 2}checked="checked"{/if}
            >
            <label for="top-right">{l s='Top-right' mod='myparcelbe'}</label>
            <br>
            <input
                    id="bottom-left"
                    type="checkbox"
                    value="3"
                    name="position[]"
                    {if $labelConfiguration.MY_PARCEL_LABEL_POSITION eq 3}checked="checked"{/if}
            >
            <label for="bottom-left">{l s='Bottom-left' mod='myparcelbe'}</label>
            <br>
            <input
                    id="bottom-right"
                    type="checkbox"
                    value="4"
                    name="position[]"
                    {if $labelConfiguration.MY_PARCEL_LABEL_POSITION eq 4}checked="checked"{/if}
            >
            <label for="bottom-right">{l s='Bottom-right' mod='myparcelbe'}</label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Close' mod='myparcelbe'}</button>
        <button type="button" id="button_print_label" class="btn btn-primary">{l s='Create and print' mod='myparcelbe'}</button>
      </div>
    </div>
  </div>
</div>
