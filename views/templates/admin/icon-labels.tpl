
{foreach from=$labels item=label}
    <label>{$label['status']}</label>
    <a href="{$label['track_link']}" >{$label['barcode']}</a>

    <button class="btn btn-primary" data-toggle="modal"
            type="button"
            data-target="#print"
    data-label-id = "{$label['id_label']}" >
        Print
    </button>

    <a href="{$link->getAdminLink('AdminLabel', true, ['action' => 'updateLabel'], ['labelId' => $label['id_label']])}">Refresh</a>

    <br>
{/foreach}

