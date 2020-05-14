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
                {l s='Delivery&Return' mod="myparcel"}
            </a>
        </li>
        <li>
            <a href="#tab-2" class="toolbar_btn btn-tab">
                {l s='Customs' mod="myparcel"}
            </a>
        </li>
    </ul>
    <div id="tab-1" class="tabs-sm clear">
        <div>
            <div class="form-row">
                <div class="col">
                    <label for="package-type-select">Select package type</label>
                    <select class="form-control" name="{Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME}" id = "package-type-select">
                        <option value="0" {if $params[Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME] == 0}selected{/if}>- Selection required -</option>
                        <option value="1" {if $params[Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME] == 1}selected{/if}>Package</option>
                        <option value="2" {if $params[Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME] == 2}selected{/if}>Mailbox package</option>
                        <option value="3" {if $params[Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME] == 3}selected{/if}>Letter</option>
                        <option value="4" {if $params[Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME] == 4}selected{/if}>Digital stamp</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" name="{Gett\MyParcel\Constant::MY_PARCEL_ONLY_RECIPIENT_CONFIGURATION_NAME}" type="checkbox" {if $params[Gett\MyParcel\Constant::MY_PARCEL_ONLY_RECIPIENT_CONFIGURATION_NAME] == 1}checked{/if} value="1" id="only-reciepient">
                    <label class="form-check-label" for="only-reciepient">
                        Only recipient
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" name="{Gett\MyParcel\Constant::MY_PARCEL_AGE_CHECK_CONFIGURATION_NAME}" type="checkbox" {if $params[Gett\MyParcel\Constant::MY_PARCEL_AGE_CHECK_CONFIGURATION_NAME] == 1}checked{/if} value="1" id="ageCheck">
                    <label class="form-check-label" for="ageCheck">
                        Age check
                    </label>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <label for="package-type-select">Select package format</label>
                    <select class="form-control" name="{Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_FORMAT_CONFIGURATION_NAME}" id="package-type-select">
                        <option value="0" {if $params[Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_FORMAT_CONFIGURATION_NAME] == 0}selected{/if}>- sSelection required -</option>
                        <option value="1" {if $params[Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_FORMAT_CONFIGURATION_NAME] == 1}selected{/if}>Normal</option>
                        <option value="2" {if $params[Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_FORMAT_CONFIGURATION_NAME] == 2}selected{/if}>Large</option>
                        <option value="3" {if $params[Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_FORMAT_CONFIGURATION_NAME] == 3}selected{/if}>Automatic</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" name="{Gett\MyParcel\Constant::MY_PARCEL_RETURN_PACKAGE_CONFIGURATION_NAME}" type="checkbox" {if $params[Gett\MyParcel\Constant::MY_PARCEL_RETURN_PACKAGE_CONFIGURATION_NAME] == 1}checked{/if} value="1" id="gridCheck">
                    <label class="form-check-label" for="gridCheck">
                        Return package
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" name="{Gett\MyParcel\Constant::MY_PARCEL_SIGNATURE_REQUIRED_CONFIGURATION_NAME}" type="checkbox" {if $params[Gett\MyParcel\Constant::MY_PARCEL_SIGNATURE_REQUIRED_CONFIGURATION_NAME] == 1}checked{/if} value="1" id="signature">
                    <label class="form-check-label" for="signature">
                        Signature
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" name= "{Gett\MyParcel\Constant::MY_PARCEL_INSURANCE_CONFIGURATION_NAME}" type="checkbox" {if $params[Gett\MyParcel\Constant::MY_PARCEL_INSURANCE_CONFIGURATION_NAME] == 1}checked{/if} value="1" id="insurance">
                    <label class="form-check-label" for="insurance">
                        Insurance
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div id="tab-2" class="tabs-sm clear" style="display: none;">
        <div>
            <div class="form-row">
                <div class="col">
                    <label for="custom-form">Custom Form</label>
                    <select class="form-control" id="custom-form" name = "{Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_FORM_CONFIGURATION_NAME}">
                        <option value="0" {if $params[Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_FORM_CONFIGURATION_NAME] == 0}selected{/if}>- Selection required -</option>
                        <option value="No" {if $params[Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_FORM_CONFIGURATION_NAME] == 1}selected{/if}>No</option>
                        <option value="Add" {if $params[Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_FORM_CONFIGURATION_NAME] == 2}selected{/if}>Add</option>
                        <option value="Skip" {if $params[Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_FORM_CONFIGURATION_NAME] == 3}selected{/if}>Skip</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="custom-code">Custom Code</label>
                <input type="text" class="form-control" id="custom-code" value="{$params[Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_CODE_CONFIGURATION_NAME]}" placeholder="Example input" name = "{Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_CODE_CONFIGURATION_NAME}">
            </div>
            <div class="form-row">
                <div class="col">
                    <label for="custom-origin">Customs Origin</label>
                    <select class="form-control" id="custom-origin" name = "{Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_ORIGIN_CONFIGURATION_NAME}" >
                        <option selected value="0">Open this select menu</option>
                        {foreach $countries as $country}
                            <option {if $params[Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_ORIGIN_CONFIGURATION_NAME] == $country['iso_code']}selected{/if} value="{$country['iso_code']}">{$country['name']}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" name="{Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_AGE_CHECK_CONFIGURATION_NAME}" type="checkbox" {if $params[Gett\MyParcel\Constant::MY_PARCEL_CUSTOMS_AGE_CHECK_CONFIGURATION_NAME] == 1}checked{/if} value="1" id="age-check">
                    <label class="form-check-label" for="age-check">
                        Customs age check
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