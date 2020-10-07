{foreach from=$labels item=label}
    <label>{$label['status']}</label>
    <a class="_blank" href="{$label['track_link']}">{$label['barcode']}</a>

    <button
            class="btn btn-link btn-print-label{if $promptForLabelPosition} label-modal{/if}"
            type="button"
            {if $promptForLabelPosition}data-target="#print"{/if}
            {if $promptForLabelPosition}data-toggle="modal"{/if}
            data-label-id="{$label['id_label']}"
    >
        <i class="material-icons">
            print
        </i>
    </button>
    <a
            href="{$link->getAdminLink('AdminMyParcelBELabel', true, [], ['action' => 'updateLabel', 'labelId' => $label['id_label']])}"
            class="btn btn-link"
    >
        <i class="material-icons">
            refresh
        </i>
    </a>

    <br>
{/foreach}

