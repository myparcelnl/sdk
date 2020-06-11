<form method="post" action="{$action}">
    <label for="labels_amount">{l s='Amout of labels' mod='myparcel'}</label>
    <input id="labels_amount" name="number" value="1" type="number" min="1" class="form-control">
    <label for="{Gett\MyParcel\Constant::PACKAGE_TYPE_CONFIGURATION_NAME}">{l s='Package type' mod='myparcel'}</label>
    <select name="{Gett\MyParcel\Constant::PACKAGE_TYPE_CONFIGURATION_NAME}" class="custom-select">
        <option value="1">{l s='Packet' mod='myparcel'}</option>
        <option value="2">{l s='Mailbox package' mod='myparcel'}</option>
        <option value="3">{l s='Letter' mod='myparcel'}</option>
        <option value="4">{l s='Digital stamp' mod='myparcel'}</option>
    </select>
    <label for="{Gett\MyParcel\Constant::ONLY_RECIPIENT_CONFIGURATION_NAME}">{l s='Only to receipient' mod='myparcel'}</label>
    <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::ONLY_RECIPIENT_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::ONLY_RECIPIENT_CONFIGURATION_NAME}">

    {if !isBE}
    <label for="{Gett\MyParcel\Constant::AGE_CHECK_CONFIGURATION_NAME}">{l s='Age check' mod='myparcel'}</label>
    <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::AGE_CHECK_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::AGE_CHECK_CONFIGURATION_NAME}">
    {/if}
    <select name="{Gett\MyParcel\Constant::PACKAGE_FORMAT_CONFIGURATION_NAME}" class="custom-select">
        <option value="1">{l s='Normal' mod='myparcel'}</option>
        <option value="2">{l s='Large' mod='myparcel'}</option>
        <option value="3">{l s='Automatic' mod='myparcel'}</option>
    </select>
    {if !isBE}
    <label for="{Gett\MyParcel\Constant::RETURN_PACKAGE_CONFIGURATION_NAME}">{l s='Return package' mod='myparcel'}</label>
    <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::RETURN_PACKAGE_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::RETURN_PACKAGE_CONFIGURATION_NAME}">
    {/if}

    <label for="{Gett\MyParcel\Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME}">{l s='Signature' mod='myparcel'}</label>
    <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME}">

    <label for="{Gett\MyParcel\Constant::INSURANCE_CONFIGURATION_NAME}">{l s='Insurnance' mod='myparcel'}</label>
    <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::INSURANCE_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::INSURANCE_CONFIGURATION_NAME}">
    <button type="submit">{l s='Submit' mod='myparcel'}</button>
</form>