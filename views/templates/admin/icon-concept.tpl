<button
        type="button"
        class="btn btn-default"
        data-toggle="modal"
        data-order-id="{$tr.id_order|intval}"
        data-label-options='{$label_options}'{* Single quotes to allow json format *}
        data-allow-set-only-recipient="{$allowSetOnlyRecipient|intval}"
        data-allow-set-signature="{$allowSetSignature|intval}"
        data-target="#create"
>
    <i class="icon-tag"></i> {l s='Create' mod='myparcelbe'}
</button>