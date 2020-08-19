<!-- Modal -->
<div class="modal fade" id="print" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{l s='Print' mod='myparcelbe'}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{$download_action}" method="post" id="print-form">
                    <input type="hidden" name="label_id" id="id_label">
                    <div class="myparcel-radio-wrapper">
                        <div class="myparcel-radio-container">
                            <input
                                    id="a4"
                                    type="radio"
                                    value="a4"
                                    name="format"
                                    class="myparcel-radio"
                                    {if $labelConfiguration.MY_PARCEL_LABEL_SIZE eq 'a4'}checked="checked"{/if}
                            >
                            <label for="a4">A4</label>
                        </div>
                        <div class="myparcel-radio-container">
                            <input
                                    id="a6"
                                    type="radio"
                                    value="a6"
                                    name="format"
                                    class="myparcel-radio"
                                    {if $labelConfiguration.MY_PARCEL_LABEL_SIZE eq 'a6'}checked="checked"{/if}
                            >
                            <label for="a6">A6</label>
                        </div>
                    </div>
                    <br>
                    <div class="positions-block">
                        <input
                                id="top-left"
                                type="checkbox"
                                value="1"
                                name="position[]"
                                {if $labelConfiguration.MY_PARCEL_LABEL_POSITION eq 1}checked="checked"{/if}
                        >
                        <label for="top-left">{l s='Top-left' mod='myparcelbe'}</label>
                        <br>
                        <input
                                id="top-right"
                                type="checkbox"
                                value="2"
                                name="position[]"
                                {if $labelConfiguration.MY_PARCEL_LABEL_POSITION eq 2}checked="checked"{/if}
                        >
                        <label for="top-right">{l s='Top-right' mod='myparcelbe'}</label>
                        <br>
                        <input
                                id="bottom-left"
                                type="checkbox"
                                value="3"
                                name="position[]"
                                {if $labelConfiguration.MY_PARCEL_LABEL_POSITION eq 3}checked="checked"{/if}
                        >
                        <label for="bottom-left">{l s='Bottom-left' mod='myparcelbe'}</label>
                        <br>
                        <input
                                id="bottom-right"
                                type="checkbox"
                                value="4"
                                name="position[]"
                                {if $labelConfiguration.MY_PARCEL_LABEL_POSITION eq 4}checked="checked"{/if}
                        >
                        <label for="bottom-right">{l s='Bottom-right' mod='myparcelbe'}</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Close' mod='myparcelbe'}</button>
                <button type="button" id="print_button" class="btn btn-primary">{l s='Save changes' mod='myparcelbe'}</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="bulk-print" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{l s='Print' mod='myparcelbe'}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{$print_bulk_action}" method="post" id="print-bulk-form">
                    <div class="myparcel-radio-wrapper">
                        <div class="myparcel-radio-container">
                            <input
                                    id="a4_bulk"
                                    type="radio"
                                    value="a4"
                                    name="format"
                                    class="myparcel-radio"
                                    {if $labelConfiguration.MY_PARCEL_LABEL_SIZE eq 'a4'}checked="checked"{/if}
                            >
                            <label for="a4_bulk">A4</label>
                        </div>
                        <div class="myparcel-radio-container">
                            <input
                                    id="a6_bulk"
                                    type="radio"
                                    value="a6"
                                    name="format"
                                    class="myparcel-radio"
                                    {if $labelConfiguration.MY_PARCEL_LABEL_SIZE eq 'a6'}checked="checked"{/if}
                            >
                            <label for="a6_bulk">A6</label>
                        </div>
                    </div>
                    <br>
                    <div class="positions-block">
                        <input
                                id="top-left-bulk"
                                type="checkbox"
                                value="1"
                                name="position[]"
                                {if $labelConfiguration.MY_PARCEL_LABEL_POSITION eq 1}checked="checked"{/if}
                        >
                        <label for="top-left-bulk">{l s='Top-left' mod='myparcelbe'}</label>
                        <br>
                        <input
                                id="top-right-bulk"
                                type="checkbox"
                                value="2"
                                name="position[]"
                                {if $labelConfiguration.MY_PARCEL_LABEL_POSITION eq 2}checked="checked"{/if}
                        >
                        <label for="top-right-bulk">{l s='Top-right' mod='myparcelbe'}</label>
                        <br>
                        <input
                                id="bottom-left-bulk"
                                type="checkbox"
                                value="3"
                                name="position[]"
                                {if $labelConfiguration.MY_PARCEL_LABEL_POSITION eq 3}checked="checked"{/if}
                        >
                        <label for="bottom-left-bulk">{l s='Print' mod='myparcelbe'}</label>
                        <br>
                        <input
                                id="bottom-right-bulk"
                                type="checkbox"
                                value="4"
                                name="position[]"
                                {if $labelConfiguration.MY_PARCEL_LABEL_POSITION eq 4}checked="checked"{/if}
                        >
                        <label for="bottom-right-bulk">{l s='Bottom-right' mod='myparcelbe'}</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Close' mod='myparcelbe'}</button>
                <button type="submit" id="print-bulk-button" class="btn btn-primary">{l s='Save changes' mod='myparcelbe'}</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{l s='Create' mod='myparcelbe'}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="print-modal">
                <input type="hidden" id="order_id" name="create_label[order_ids][]">
                <div class="form-group">
                    <label for="labels_amount">{l s='Amount of labels' mod='myparcelbe'}</label>
                    <input id="labels_amount" name="number" value="1" type="number" min="1" class="form-control">
                </div>

                <div class="form-group">
                    <label for="package-type">{l s='Package type' mod='myparcelbe'}</label>
                    <select name="{$PACKAGE_TYPE}" id="package-type" class="custom-select">
                        <option value="1">{l s='Parcel' mod='myparcelbe'}</option>
                        <option value="2">{l s='Mailbox package' mod='myparcelbe'}</option>
                        <option value="3">{l s='Letter' mod='myparcelbe'}</option>
                        <option value="4">{l s='Digital stamp' mod='myparcelbe'}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="{$PACKAGE_FORMAT}">{l s='Package format' mod='myparcelbe'}</label>
                    <select name="{$PACKAGE_FORMAT}" class="custom-select" id="{$PACKAGE_FORMAT}">
                        <option value="1">{l s='Normal' mod='myparcelbe'}</option>
                        <option value="2">{l s='Large' mod='myparcelbe'}</option>
                        <option value="3">{l s='Automatic' mod='myparcelbe'}</option>
                    </select>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="{$ONLY_RECIPIENT}" name="{$ONLY_RECIPIENT}">
                    <label for="{$ONLY_RECIPIENT}">{l s='Only to receipient' mod='myparcelbe'}</label>
                </div>

                {if !$isBE}
                <div class="form-group">
                    <input type="checkbox" value="1" id="{$AGE_CHECK}" name="{$AGE_CHECK}">
                    <label for="{$AGE_CHECK}">{l s='Age check' mod='myparcelbe'}</label>
                </div>
                {/if}

                {if !$isBE}
                <div class="form-group">
                    <input type="checkbox" value="1" id="{$RETURN_PACKAGE}" name="{$RETURN_PACKAGE}">
                    <label for="{$RETURN_PACKAGE}">{l s='Return package' mod='myparcelbe'}</label>
                </div>
                {/if}

                <div class="form-group">
                    <input type="checkbox" value="1" id="{$SIGNATURE_REQUIRED}" name="{$SIGNATURE_REQUIRED}">
                    <label for="{$SIGNATURE_REQUIRED}">{l s='Signature' mod='myparcelbe'}</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" class="myparcel-insurance-checkbox" value="1" id="{$INSURANCE}" name="{$INSURANCE}">
                    <label for="{$INSURANCE}">{l s='Insurance' mod='myparcelbe'}</label>
                </div>
                <div class="insurance-additional-container">
                    <div class="form-group insurance-additional-predefined">
                        <div class="myparcel-radio-container">
                            <input
                                    id="upto100"
                                    type="radio"
                                    value="100"
                                    name="insurance-value-option"
                                    class="myparcel-radio"
                                    checked
                            >
                            <label for="upto100">{l s='Up to € 100' mod='myparcelbe'}</label>
                        </div>
                        <div class="myparcel-radio-container">
                            <input id="upto250" type="radio" value="250" name="insurance-value-option" class="myparcel-radio">
                            <label for="upto250">{l s='Up to € 250' mod='myparcelbe'}</label>
                        </div>
                        <div class="myparcel-radio-container">
                            <input id="upto500" type="radio" value="500" name="insurance-value-option" class="myparcel-radio">
                            <label for="upto500">{l s='Up to € 500' mod='myparcelbe'}</label>
                        </div>
                    </div>
                    {if !$isBE}
                        <div class="form-group">
                            <div class="myparcel-radio-container">
                                <input id="heigherthen500" type="radio" value="4" class="myparcel-radio" name="heigherthen500">
                                <label for="heigherthen500">{l s='Higher than € 500' mod='myparcelbe'}</label>
                            </div>
                            <div class="money-input-wrapper">
                                <div class="input-group money-type">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">€ </span>
                                    </div>
                                    <input
                                            type="text"
                                            id="myparcel-insurance-higher-amount"
                                            name="insurance-higher-amount"
                                            class="form-control"
                                            value="1000"
                                    />
                                </div>
                            </div>
                        </div>
                    {/if}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Close' mod='myparcelbe'}</button>
                <button type="button" id="add" class="btn btn-primary">{l s='Save changes' mod='myparcelbe'}</button>
            </div>
        </div>
    </div>
</div>