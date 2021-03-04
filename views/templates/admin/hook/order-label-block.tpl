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
              <span class="delivery-options-span" data-toggle="modal" data-target="#deliveryDateModal">
                {dateFormat date=$deliveryOptions.date full=false}
                <i class="icon-pencil"></i>
              </span>
            </div>
          </button>
        </div>
        <div class="concept-label-wrapper form-wrapper">
          {$labelConceptHtml}
        </div>
        <div class="concept-label-address">
          <div class="well">
            <a class="btn btn-default pull-right" href="{$addressEditUrl}">
              <i class="icon-pencil"></i> {l s='Edit' d='Admin.Actions'}
            </a>
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
                    data-toggle="{if empty($promptForLabelPosition)}no-{/if}modal"
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
        <form class="shipment-labels-wrapper" action="{$labelUrl}" method="post">
          <div class="table-responsive">
            {$labelListHtml}
          </div>
          <div class="btn-group bulk-actions dropup shipment-labels-bulk-actions">
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
                                class="bulk-actions-links"
                                href="#"
                                data-action="bulkAction{$key|ucfirst}"
                                data-ajax="{if isset($params.ajax)}{$params.ajax|intval}{else}0{/if}"
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

{* Modal Print *}
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
                      {if $labelConfiguration.MYPARCELBE_LABEL_SIZE eq 'a4'}checked="checked"{/if}
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
                      {if $labelConfiguration.MYPARCELBE_LABEL_SIZE eq 'a6'}checked="checked"{/if}
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
                    {if 1 / $labelConfiguration.MYPARCELBE_LABEL_POSITION >= 1}checked="checked"{/if}
            >
            <label for="top-left">{l s='Top-left' mod='myparcelbe'}</label>
            <br>
            <input
                    id="top-right"
                    type="checkbox"
                    value="2"
                    name="position[]"
                    {if 2 / $labelConfiguration.MYPARCELBE_LABEL_POSITION >= 1}checked="checked"{/if}
            >
            <label for="top-right">{l s='Top-right' mod='myparcelbe'}</label>
            <br>
            <input
                    id="bottom-left"
                    type="checkbox"
                    value="3"
                    name="position[]"
                    {if 3 / $labelConfiguration.MYPARCELBE_LABEL_POSITION >= 1}checked="checked"{/if}
            >
            <label for="bottom-left">{l s='Bottom-left' mod='myparcelbe'}</label>
            <br>
            <input
                    id="bottom-right"
                    type="checkbox"
                    value="4"
                    name="position[]"
                    {if 4 / $labelConfiguration.MYPARCELBE_LABEL_POSITION >= 1}checked="checked"{/if}
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

{* Modal Delivery Date change *}
<div class="modal fade" id="deliveryDateModal" tabindex="-1" role="dialog" aria-labelledby="deliveryDateModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deliveryDateModalTitle">{l s='Delivery Options' mod='myparcelbe'}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div data-url="{$labelUrl}" id="deliveryDateUpdateWrapper" class="myparcel-delivery-options-wrapper">
          <form class="hidden-input-fields-form" action="{$labelUrl}" method="post">
            <input type="hidden" name="action" value="deliveryDateUpdate">
            <input type="hidden" name="id_carrier" value="{$id_carrier}">
            <input type="hidden" name="id_order" value="{$id_order}">
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Close' mod='myparcelbe'}</button>
        <button type="button" id="buttonDeliveryDateUpdate" class="btn btn-primary" data-url="{$labelUrl}">
          {l s='Save' mod='myparcelbe'}
        </button>
      </div>
    </div>
  </div>
</div>

{* Modal Label Return form *}
<div class="modal fade" id="labelReturnModal" tabindex="-1" role="dialog" aria-labelledby="labelReturnModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="labelReturnModalTitle">{l s='Email return label to your customer' mod='myparcelbe'}</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          {$labelReturnHtml}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Close' mod='myparcelbe'}</button>
        <button type="button" id="buttonLabelReturn" class="btn btn-primary" data-action="createReturnLabel" data-ajax="1">
            {l s='Create' mod='myparcelbe'}
        </button>
      </div>
    </div>
  </div>
</div>
