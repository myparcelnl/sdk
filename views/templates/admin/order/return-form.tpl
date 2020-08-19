<form method="post" action="{$action}">
    <label for="labels_amount">{l s='Amout of labels' mod='myparcelbe'}</label>
    <input id="labels_amount" name="number" value="1" type="number" min="1" class="form-control">

    <label for="{$PACKAGE_TYPE}">{l s='Package type' mod='myparcelbe'}</label>
    <select name="{$PACKAGE_TYPE}" class="custom-select" id="{$PACKAGE_TYPE}">
        <option value="1">{l s='Parcel' mod='myparcelbe'}</option>
        <option value="2">{l s='Mailbox package' mod='myparcelbe'}</option>
        <option value="3">{l s='Letter' mod='myparcelbe'}</option>
        <option value="4">{l s='Digital stamp' mod='myparcelbe'}</option>
    </select>

    <label for="{$PACKAGE_FORMAT}">{l s='Package format' mod='myparcelbe'}</label>
    <select name="{$PACKAGE_FORMAT}" class="custom-select" id="{$PACKAGE_FORMAT}">
        <option value="1">{l s='Normal' mod='myparcelbe'}</option>
        <option value="2">{l s='Large' mod='myparcelbe'}</option>
        <option value="3">{l s='Automatic' mod='myparcelbe'}</option>
    </select>

    <label for="{$ONLY_RECIPIENT}">{l s='Only to receipient' mod='myparcelbe'}</label>
    <input type="checkbox" value="1" id="{$ONLY_RECIPIENT}" name="{$ONLY_RECIPIENT}">

    {if !isBE}
        <label for="{$AGE_CHECK}">{l s='Age check' mod='myparcelbe'}</label>
        <input type="checkbox" value="1" id="{$AGE_CHECK}" name="{$AGE_CHECK}">
    {/if}

    {if !isBE}
        <label for="{$RETURN_PACKAGE}">{l s='Return package' mod='myparcelbe'}</label>
        <input type="checkbox" value="1" id="{$RETURN_PACKAGE}" name="{$RETURN_PACKAGE}">
    {/if}

    <label for="{$SIGNATURE_REQUIRED}">{l s='Signature' mod='myparcelbe'}</label>
    <input type="checkbox" value="1" id="{$SIGNATURE_REQUIRED}" name="{$SIGNATURE_REQUIRED}">

    <label for="{$INSURANCE}">{l s='Insurance' mod='myparcelbe'}</label>
    <input type="checkbox" value="1" id="{$INSURANCE}" name="{$INSURANCE}">

    <button type="submit">{l s='Submit' mod='myparcelbe'}</button>
</form>