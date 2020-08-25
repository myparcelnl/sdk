<div id="myparcel-order-panel" class="panel">
  <div class="panel-heading">
    <img src="{$modulePathUri}views/images/myparcelnl-grayscale.png" alt="" /> {l s='MyParcel' mod='myparcelbe'}
  </div>
  <div class="row">
    <div class="col-lg-6">
      <div class="panel form-horizontal">
        <div class="panel-heading">
          <i class="icon-file-text"></i> {l s='Concept' mod='myparcelbe'}
        </div>
        <div class="concept-label-wrapper form-wrapper">
          <div class="form-group">
            <label class="col-lg-3 control-label">{l s='Package type' mod='myparcelbe'}</label>
            <div class="col-lg-3">
              <select name="{$PACKAGE_FORMAT}" class="form-control custom-select" id="{$PACKAGE_FORMAT}">
                <option value="1">{l s='Normal' mod='myparcelbe'}</option>
                <option value="2">{l s='Large' mod='myparcelbe'}</option>
                <option value="3">{l s='Automatic' mod='myparcelbe'}</option>
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
              <p class="checkbox">
                <label class="control-label text-left" for="label_extra_large">
                  {l s='Extra large' mod='myparcelbe'}
                  <input name="extra_large" type="checkbox" id="label_extra_large" value="1" />
                </label>
              </p>
              <p class="checkbox">
                <label class="control-label text-left" for="label_recipient_only">
                    {l s='Recipient only' mod='myparcelbe'}
                  <input name="recipient_only" type="checkbox" id="label_recipient_only" value="1" />
                </label>
              </p>
              <p class="checkbox">
                <label class="control-label text-left" for="label_require_signature">
                    {l s='Requires a signature' mod='myparcelbe'}
                  <input name="require_signature" type="checkbox" id="label_require_signature" value="1" />
                </label>
              </p>
              <p class="checkbox">
                <label class="control-label text-left" for="label_return">
                    {l s='Return when undeliverable' mod='myparcelbe'}
                  <input name="return" type="checkbox" id="label_return" value="1" />
                </label>
              </p>
              <p class="checkbox">
                <label class="control-label text-left" for="label_age_check">
                    {l s='Age check 18+' mod='myparcelbe'}
                  <input name="age_check" type="checkbox" id="label_age_check" value="1" />
                </label>
              </p>
              <p class="checkbox">
                <label class="control-label text-left" for="label_insurance">
                    {l s='Insurance' mod='myparcelbe'}
                  <input name="insurance" type="checkbox" id="label_insurance" value="1" />
                </label>
              </p>
            </div>
          </div>
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
          <button type="submit" name="submitCreateConcept" class="btn btn-default">
            <i class="icon-save"></i> {l s='Save' mod='myparcelbe'}
          </button>
          <div class="btn-group">
            <button class="btn btn-default" type="button">
              <i class="icon-plus"></i> {l s='New shipment' mod='myparcelbe'}
            </button>
            <button class="btn btn-default" type="button">
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
                    <td></td>
                    <td></td>
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
                        <a href="#" onclick="{if isset($params.confirm)}if (confirm('{$params.confirm}')){/if}sendBulkAction($(this).closest('.shipment-labels-wrapper').get(0), 'submitBulk{$key}');">
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
