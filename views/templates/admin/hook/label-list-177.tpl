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
  <tr class="tr-empty-notice{if !empty($labelList)} hidden d-none{/if}">
    <td class="list-empty hidden-print" colspan="5">
      <div class="list-empty-msg text-center">
        <span class="material-icons">warning</span>
        <div>{l s='There are no shipments' mod='myparcelbe'}</div>
      </div>
    </td>
  </tr>
  {if !empty($labelList)}
      {foreach $labelList as $label}
        <tr
                data-id="{$label.id_order_label}"
                data-label-id="{$label.id_label}"
                data-return="{l s='Return - %s' mod='myparcelbe' sprintf=[$label.barcode]}"
                class="tr-label-item"
        >
          <td><input type="checkbox" name="labelBox[]" class="noborder" value="{$label.id_order_label}"></td>
          <td>
            <a href="{$label.track_link}" target="_blank" rel="noopener noreferrer">{$label.barcode}</a>
          </td>
          <td>{$label.status}</td>
          <td>{dateFormat date=$label.date_upd full=true}</td>
          <td class="order-label-action text-right">
            <div class="btn-group" id="btn_group_action">
              <button
                      type="button"
                      class="btn btn-primary btn-sm order-label-action-print{if $promptForLabelPosition} label-modal{/if}"
                      {if $promptForLabelPosition}data-target="#printLabelModal"{/if}
                      {if $promptForLabelPosition}data-toggle="modal"{/if}
              >
                <span class="material-icons">local_printshop</span> {l s='Print' mod='myparcelbe'}
              </button>
              <button
                      type="button"
                      class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split"
                      data-toggle="dropdown"
                      aria-haspopup="true"
                      aria-expanded="false"
              >
                <span class="sr-only">{l s='Toggle Dropdown' mod='myparcelbe'}</span>
              </button>
              <div class="dropdown-menu">
                <a href="#" class="order-label-action-refresh dropdown-item">
                  <span class="material-icons">refresh</span>
                  {l s='Refresh' mod='myparcelbe'}
                </a>
                {if !empty($label.ALLOW_RETURN_FORM)}
                  <a href="#" class="order-label-action-return dropdown-item{if !empty($label.return_disabled)} disabled{/if}">
                    <span class="material-icons">reply</span>
                    {l s='Create return label' mod='myparcelbe'}
                  </a>
                {/if}
                <div class="dropdown-divider"></div>
                <a href="#" class="order-label-action-delete dropdown-item">
                  <span class="material-icons">delete</span>
                  {l s='Delete' d='Admin.Actions'}
                </a>
              </div>
            </div>
          </td>
        </tr>
      {/foreach}
  {/if}
  </tbody>
</table>
