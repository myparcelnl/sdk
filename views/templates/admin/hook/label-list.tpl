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
  <tr class="tr-empty-notice{if !empty($labelList)} hidden{/if}">
    <td class="list-empty hidden-print" colspan="5">
      <div class="list-empty-msg">
        <i class="icon-exclamation-triangle"></i>
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
                      class="btn btn-default order-label-action-print{if $promptForLabelPosition} label-modal{/if}"
                      {if $promptForLabelPosition}data-target="#printLabelModal"{/if}
                      {if $promptForLabelPosition}data-toggle="modal"{/if}
              >
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
                {if !empty($label.ALLOW_RETURN_FORM)}
                  <li>
                    <a href="#" class="order-label-action-return{if !empty($label.return_disabled)} disabled{/if}">
                      <i class="icon-reply"></i> {l s='Create return label' mod='myparcelbe'}
                    </a>
                  </li>
                {/if}
                <li class="divider"></li>
                <li>
                  <a href="#" class="order-label-action-delete">
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
