<form method="post" action="{$action}">
    <label for="labels_amount">{l s='Amout of labels' d='Modules.Myparcel.Front'}</label>
    <input id="labels_amount" name="number" value="1" type="number" min="1" class="form-control">
    <label for="{Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME}">{l s='Package type' d='Modules.Myparcel.Front'}</label>
    <select name="{Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME}" class="custom-select">
        <option value="1">{l s='Packet' d='Modules.Myparcel.Front'}</option>
        <option value="2">{l s='Mailbox package' d='Modules.Myparcel.Front'}</option>
        <option value="3">{l s='Letter' d='Modules.Myparcel.Front'}</option>
        <option value="4">{l s='Digital stamp' d='Modules.Myparcel.Front'}</option>
    </select>
    <label for="{Gett\MyParcel\Constant::MY_PARCEL_ONLY_RECIPIENT_CONFIGURATION_NAME}">{l s='Only to receipient' d='Modules.Myparcel.Front'}</label>
    <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::MY_PARCEL_ONLY_RECIPIENT_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::MY_PARCEL_ONLY_RECIPIENT_CONFIGURATION_NAME}">

    <label for="{Gett\MyParcel\Constant::MY_PARCEL_AGE_CHECK_CONFIGURATION_NAME}">{l s='Age check' d='Modules.Myparcel.Front'}</label>
    <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::MY_PARCEL_AGE_CHECK_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::MY_PARCEL_AGE_CHECK_CONFIGURATION_NAME}">
    <select name="{Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_FORMAT_CONFIGURATION_NAME}" class="custom-select">
        <option value="1">{l s='Normal' d='Modules.Myparcel.Front'}</option>
        <option value="2">{l s='Large' d='Modules.Myparcel.Front'}</option>
        <option value="3">{l s='Automatic' d='Modules.Myparcel.Front'}</option>
    </select>
    <label for="{Gett\MyParcel\Constant::MY_PARCEL_RETURN_PACKAGE_CONFIGURATION_NAME}">{l s='Return package' d='Modules.Myparcel.Front'}</label>
    <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::MY_PARCEL_RETURN_PACKAGE_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::MY_PARCEL_RETURN_PACKAGE_CONFIGURATION_NAME}">

    <label for="{Gett\MyParcel\Constant::MY_PARCEL_SIGNATURE_REQUIRED_CONFIGURATION_NAME}">{l s='Signature' d='Modules.Myparcel.Front'}</label>
    <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::MY_PARCEL_SIGNATURE_REQUIRED_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::MY_PARCEL_SIGNATURE_REQUIRED_CONFIGURATION_NAME}">

    <label for="{Gett\MyParcel\Constant::MY_PARCEL_INSURANCE_CONFIGURATION_NAME}">{l s='Insurnance' d='Modules.Myparcel.Front'}</label>
    <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::MY_PARCEL_INSURANCE_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::MY_PARCEL_INSURANCE_CONFIGURATION_NAME}">
    <button type="submit">{l s='Submit' d='Modules.Myparcel.Front'}</button>
</form>