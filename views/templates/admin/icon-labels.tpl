{foreach from=$labels item=label}
    <label>{$label['status']}</label>
    <a class="_blank" href="{$label['track_link']}">{$label['barcode']}</a>

    <button class="btn btn-link" data-toggle="modal"
            type="button"
            data-target="#print"
    data-label-id = "{$label['id_label']}" >
        <i class="material-icons">
            print
        </i>
    </button>
    <a href="{$link->getAdminLink('Label', true, [], ['action' => 'updateLabel', 'labelId' => $label['id_label']])}" class="btn btn-link">
        <i class="material-icons">
            refresh
        </i>
    </a>

    <br>
{/foreach}

