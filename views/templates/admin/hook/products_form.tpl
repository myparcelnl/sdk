<style>
    .form-group > .col-lg-8 {
        overflow: hidden;
        margin-bottom: 20px;
    }

    .list-tab {
        text-align: center;
        border-bottom: 1px solid #dcdcdc;
        margin-bottom: 30px !important;
        overflow: hidden;
    }

    .list-tab li {
        list-style: none;
        display: inline-block;
        border: 1px solid #dcdcdc;
        border-width: 1px 1px 0;
    }

    .list-tab li a {
        color: #3d3d3d;
        padding: 10px 30px;
        text-align: center;
        display: block;
        text-decoration: none !important;
    }

    .list-tab li a.active,
    .list-tab li a:hover {
        background: #00aff0;
        color: #fff;
    }
</style>
<div id="show-block">
    <ul class="list-tab">
        <li>
            <a href="#tab-1" class="toolbar_btn btn-tab active">
                {l s='Delivery&Return' d='Modules.Myparcel.Front'}
            </a>
        </li>
        <li>
            <a href="#tab-2" class="toolbar_btn btn-tab">
                {l s='Customs' d='Modules.Myparcel.Front'}
            </a>
        </li>
    </ul>
    <div id="tab-1" class="tabs-sm clear row">
        <div class="col-lg-4">
            <div class="form-group">
                <label for="package-type-select">{l s='Select package type' d='Modules.Myparcel.Front'}</label>
                <select class="form-control" name="{Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME}" id = "package-type-select">
                    <option value="1" {if $params[Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME] == 1}selected{/if}>{l s='Package' d='Modules.Myparcel.Front'}</option>
                    <option value="2" {if $params[Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME] == 2}selected{/if}>{l s='Mailbox package' d='Modules.Myparcel.Front'}</option>
                    <option value="3" {if $params[Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME] == 3}selected{/if}>{l s='Letter' d='Modules.Myparcel.Front'}</option>
                    <option value="4" {if $params[Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME] == 4}selected{/if}>{l s='Digital stamp' d='Modules.Myparcel.Front'}</option>
                </select>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <label>
                        <input class="form-check-input" name="{Gett\MyParcel\Constant::MY_PARCEL_ONLY_RECIPIENT_CONFIGURATION_NAME}" type="checkbox" {if $params[Gett\MyParcel\Constant::MY_PARCEL_ONLY_RECIPIENT_CONFIGURATION_NAME] == 1}checked{/if} value="1" id="only-reciepient">
                        {l s='Only recipient' d='Modules.Myparcel.Front'}
                    </label>
                </div>
                <div class="form-check">
                    <label>
                        <input class="form-check-input" name="{Gett\MyParcel\Constant::MY_PARCEL_AGE_CHECK_CONFIGURATION_NAME}" type="checkbox" {if $params[Gett\MyParcel\Constant::MY_PARCEL_AGE_CHECK_CONFIGURATION_NAME] == 1}checked{/if} value="1" id="ageCheck">
                        {l s='Age check' d='Modules.Myparcel.Front'}
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="package-type-select">{l s='Select package format' d='Modules.Myparcel.Front'}</label>
                <select class="form-control" name="{Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_FORMAT_CONFIGURATION_NAME}" id="package-type-select">
                    <option value="1" {if $params[Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_FORMAT_CONFIGURATION_NAME] == 1}selected{/if}>{l s='Normal' d='Modules.Myparcel.Front'}</option>
                    <option value="2" {if $params[Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_FORMAT_CONFIGURATION_NAME] == 2}selected{/if}>{l s='Large' d='Modules.Myparcel.Front'}</option>
                    <option value="3" {if $params[Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_FORMAT_CONFIGURATION_NAME] == 3}selected{/if}>{l s='Automatic' d='Modules.Myparcel.Front'}</option>
                </select>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <label>
                        <input class="form-check-input" name="{Gett\MyParcel\Constant::MY_PARCEL_RETURN_PACKAGE_CONFIGURATION_NAME}" type="checkbox" {if $params[Gett\MyParcel\Constant::MY_PARCEL_RETURN_PACKAGE_CONFIGURATION_NAME] == 1}checked{/if} value="1" id="gridCheck">
                        {l s='Return package' d='Modules.Myparcel.Front'}
                    </label>
                </div>
                <div class="form-check">
                    <label>
                        <input class="form-check-input" name="{Gett\MyParcel\Constant::MY_PARCEL_SIGNATURE_REQUIRED_CONFIGURATION_NAME}" type="checkbox" {if $params[Gett\MyParcel\Constant::MY_PARCEL_SIGNATURE_REQUIRED_CONFIGURATION_NAME] == 1}checked{/if} value="1" id="signature">
                        {l s='Signature' d='Modules.Myparcel.Front'}
                    </label>
                </div>
                <div class="form-check">
                    <label>
                        <input class="form-check-input" name= "{Gett\MyParcel\Constant::MY_PARCEL_INSURANCE_CONFIGURATION_NAME}" type="checkbox" {if $params[Gett\MyParcel\Constant::MY_PARCEL_INSURANCE_CONFIGURATION_NAME] == 1}checked{/if} value="1" id="insurance">
                        {l s='Insurance' d='Modules.Myparcel.Front'}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div id="tab-2" class="tabs-sm clear row" style="display: none;">
        <div class="col-lg-4">
            <div class="form-group">
                <label for="custom-form">{l s='Custom Form' d='Modules.Myparcel.Front'}</label>
                <select class="form-control" id="custom-form" name = "{Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_FORM_CONFIGURATION_NAME}">
                    <option value="No" {if $params[Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_FORM_CONFIGURATION_NAME] == 'No'}selected{/if}>{l s='Do not automatically generate customs form' d='Modules.Myparcel.Front'}</option>
                    <option value="Add" {if $params[Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_FORM_CONFIGURATION_NAME] == 'Add'}selected{/if}>{l s='Add this product to customs form' d='Modules.Myparcel.Front'}</option>
                    <option value="Skip" {if $params[Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_FORM_CONFIGURATION_NAME] == 'Skip'}selected{/if}>{l s='Skip this product on customs form' d='Modules.Myparcel.Front'}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="custom-code">{l s='Custom Code' d='Modules.Myparcel.Front'}</label>
                <input type="text" class="form-control" id="custom-code" value="{$params[Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_CODE_CONFIGURATION_NAME]}" placeholder="Example input" name = "{Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_CODE_CONFIGURATION_NAME}">
            </div>
            <div class="form-group">
                <label for="custom-origin">{l s='Customs Origin' d='Modules.Myparcel.Front'}</label>
                <select class="form-control" id="custom-origin" name = "{Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_ORIGIN_CONFIGURATION_NAME}" >
                    {foreach $countries as $country}
                        <option {if $params[Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_ORIGIN_CONFIGURATION_NAME] == $country['iso_code']}selected{/if} value="{$country['iso_code']}">{$country['name']}</option>
                    {/foreach}
                </select>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <label>
                        <input class="form-check-input" name="{Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_AGE_CHECK_CONFIGURATION_NAME}" type="checkbox" {if $params[Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_AGE_CHECK_CONFIGURATION_NAME] == 1}checked{/if} value="1" id="age-check">
                        {l s='Customs age check' d='Modules.Myparcel.Front'}
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#show-block').tabs();
    $(document).on('click', '.btn-tab', function(){
        $('.btn-tab').removeClass('active');
        $(this).addClass('active');
    });
</script>