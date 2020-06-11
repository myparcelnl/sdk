<form method="post" action="{$action}">
    <label for="labels_amount">{l s='Amout of labels' mod='myparcelbe'}</label>
    <input id="labels_amount" name="number" value="1" type="number" min="1" class="form-control">
    <label for="{Gett\MyparcelBE\Constant::PACKAGE_TYPE_CONFIGURATION_NAME}">{l s='Package type' mod='myparcelbe'}</label>
    <select name="{Gett\MyparcelBE\Constant::PACKAGE_TYPE_CONFIGURATION_NAME}" class="custom-select">
        <option value="1">{l s='Packet' mod='myparcelbe'}</option>
        <option value="2">{l s='Mailbox package' mod='myparcelbe'}</option>
        <option value="3">{l s='Letter' mod='myparcelbe'}</option>
        <option value="4">{l s='Digital stamp' mod='myparcelbe'}</option>
    </select>
    <label for="{Gett\MyparcelBE\Constant::ONLY_RECIPIENT_CONFIGURATION_NAME}">{l s='Only to receipient' mod='myparcelbe'}</label>
    <input type="checkbox" value="1" id="{Gett\MyparcelBE\Constant::ONLY_RECIPIENT_CONFIGURATION_NAME}" name="{Gett\MyparcelBE\Constant::ONLY_RECIPIENT_CONFIGURATION_NAME}">

    {if !isBE}
    <label for="{Gett\MyparcelBE\Constant::AGE_CHECK_CONFIGURATION_NAME}">{l s='Age check' mod='myparcelbe'}</label>
    <input type="checkbox" value="1" id="{Gett\MyparcelBE\Constant::AGE_CHECK_CONFIGURATION_NAME}" name="{Gett\MyparcelBE\Constant::AGE_CHECK_CONFIGURATION_NAME}">
    {/if}
    <select name="{Gett\MyparcelBE\Constant::PACKAGE_FORMAT_CONFIGURATION_NAME}" class="custom-select">
        <option value="1">{l s='Normal' mod='myparcelbe'}</option>
        <option value="2">{l s='Large' mod='myparcelbe'}</option>
        <option value="3">{l s='Automatic' mod='myparcelbe'}</option>
    </select>
    {if !isBE}
    <label for="{Gett\MyparcelBE\Constant::RETURN_PACKAGE_CONFIGURATION_NAME}">{l s='Return package' mod='myparcelbe'}</label>
    <input type="checkbox" value="1" id="{Gett\MyparcelBE\Constant::RETURN_PACKAGE_CONFIGURATION_NAME}" name="{Gett\MyparcelBE\Constant::RETURN_PACKAGE_CONFIGURATION_NAME}">
    {/if}

    <label for="{Gett\MyparcelBE\Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME}">{l s='Signature' mod='myparcelbe'}</label>
    <input type="checkbox" value="1" id="{Gett\MyparcelBE\Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME}" name="{Gett\MyparcelBE\Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME}">

    <label for="{Gett\MyparcelBE\Constant::INSURANCE_CONFIGURATION_NAME}">{l s='Insurnance' mod='myparcelbe'}</label>
    <input type="checkbox" value="1" id="{Gett\MyparcelBE\Constant::INSURANCE_CONFIGURATION_NAME}" name="{Gett\MyparcelBE\Constant::INSURANCE_CONFIGURATION_NAME}">
    <button type="submit">{l s='Submit' mod='myparcelbe'}</button>
</form>