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
                                    class="myparcel-radio page-format-radio page-format-a4"
                                    {if $labelConfiguration.MYPARCELBE_LABEL_SIZE eq 'a4'}checked="checked"{/if}
                            >
                            <label for="a4">{l s='A4' mod='myparcelbe'}</label>
                        </div>
                        <div class="myparcel-radio-container">
                            <input
                                    id="a6"
                                    type="radio"
                                    value="a6"
                                    name="format"
                                    class="myparcel-radio page-format-radio page-format-a6"
                                    {if $labelConfiguration.MYPARCELBE_LABEL_SIZE eq 'a6'}checked="checked"{/if}
                            >
                            <label for="a6">{l s='A6' mod='myparcelbe'}</label>
                        </div>
                    </div>
                    <br>
                    <div class="positions-block">
                        <input
                                id="top-left"
                                type="checkbox"
                                value="1"
                                name="position[]"
                                {if 1 / $labelConfiguration.MYPARCELBE_LABEL_POSITION >= 1}checked="checked"{/if}
                        >
                        <label for="top-left">{l s='Top-left' mod='myparcelbe'}</label>
                        <br>
                        <input
                                id="top-right"
                                type="checkbox"
                                value="2"
                                name="position[]"
                                {if 2 / $labelConfiguration.MYPARCELBE_LABEL_POSITION >= 1}checked="checked"{/if}
                        >
                        <label for="top-right">{l s='Top-right' mod='myparcelbe'}</label>
                        <br>
                        <input
                                id="bottom-left"
                                type="checkbox"
                                value="3"
                                name="position[]"
                                {if 3 / $labelConfiguration.MYPARCELBE_LABEL_POSITION >= 1}checked="checked"{/if}
                        >
                        <label for="bottom-left">{l s='Bottom-left' mod='myparcelbe'}</label>
                        <br>
                        <input
                                id="bottom-right"
                                type="checkbox"
                                value="4"
                                name="position[]"
                                {if 4 / $labelConfiguration.MYPARCELBE_LABEL_POSITION >= 1}checked="checked"{/if}
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
                                    class="myparcel-radio page-format-radio page-format-a4"
                                    {if $labelConfiguration.MYPARCELBE_LABEL_SIZE eq 'a4'}checked="checked"{/if}
                            >
                            <label for="a4_bulk">{l s='A4' mod='myparcelbe'}</label>
                        </div>
                        <div class="myparcel-radio-container">
                            <input
                                    id="a6_bulk"
                                    type="radio"
                                    value="a6"
                                    name="format"
                                    class="myparcel-radio page-format-radio page-format-a6"
                                    {if $labelConfiguration.MYPARCELBE_LABEL_SIZE eq 'a6'}checked="checked"{/if}
                            >
                            <label for="a6_bulk">{l s='A6' mod='myparcelbe'}</label>
                        </div>
                    </div>
                    <br>
                    <div class="positions-block">
                        <input
                                id="top-left-bulk"
                                type="checkbox"
                                value="1"
                                name="position[]"
                                {if 1 / $labelConfiguration.MYPARCELBE_LABEL_POSITION >= 1}checked="checked"{/if}
                        >
                        <label for="top-left-bulk">{l s='Top-left' mod='myparcelbe'}</label>
                        <br>
                        <input
                                id="top-right-bulk"
                                type="checkbox"
                                value="2"
                                name="position[]"
                                {if 2 / $labelConfiguration.MYPARCELBE_LABEL_POSITION >= 1}checked="checked"{/if}
                        >
                        <label for="top-right-bulk">{l s='Top-right' mod='myparcelbe'}</label>
                        <br>
                        <input
                                id="bottom-left-bulk"
                                type="checkbox"
                                value="3"
                                name="position[]"
                                {if 3 / $labelConfiguration.MYPARCELBE_LABEL_POSITION >= 1}checked="checked"{/if}
                        >
                        <label for="bottom-left-bulk">{l s='Bottom-left' mod='myparcelbe'}</label>
                        <br>
                        <input
                                id="bottom-right-bulk"
                                type="checkbox"
                                value="4"
                                name="position[]"
                                {if 4 / $labelConfiguration.MYPARCELBE_LABEL_POSITION >= 1}checked="checked"{/if}
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
                <input type="hidden" id="order_id" name="id_order">
                <div class="form-group">
                    <label for="labels_amount">{l s='Amount of labels' mod='myparcelbe'}</label>
                    <input id="labels_amount" name="label_amount" value="1" type="number" min="1" class="form-control">
                </div>

                <div class="form-group">
                    <label for="packageType">{l s='Package type' mod='myparcelbe'}</label>
                    <select name="packageType" class="custom-select" id="packageType">
                        <option value="1">{l s='Parcel' mod='myparcelbe'}</option>
                        <option value="2">{l s='Mailbox package' mod='myparcelbe'}</option>
                        <option value="3">{l s='Letter' mod='myparcelbe'}</option>
                        <option value="4">{l s='Digital stamp' mod='myparcelbe'}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="packageFormat">{l s='Package format' mod='myparcelbe'}</label>
                    <select name="packageFormat" class="custom-select" id="packageFormat">
                        <option value="1">{l s='Normal' mod='myparcelbe'}</option>
                        <option value="2">{l s='Large' mod='myparcelbe'}</option>
                        <option value="3">{l s='Automatic' mod='myparcelbe'}</option>
                    </select>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="onlyRecipient" name="onlyRecipient">
                    <label for="onlyRecipient">{l s='Only to receipient' mod='myparcelbe'}</label>
                </div>

                {if !$isBE}
                <div class="form-group">
                    <input type="checkbox" value="1" id="ageCheck" name="ageCheck">
                    <label for="ageCheck">{l s='Age check' mod='myparcelbe'}</label>
                </div>
                {/if}

                {if !$isBE}
                <div class="form-group">
                    <input type="checkbox" value="1" id="returnUndelivered" name="returnUndelivered">
                    <label for="returnUndelivered">{l s='Return package' mod='myparcelbe'}</label>
                </div>
                {/if}

                <div class="form-group">
                    <input type="checkbox" value="1" id="signatureRequired" name="signatureRequired">
                    <label for="signatureRequired">{l s='Signature' mod='myparcelbe'}</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" class="myparcel-insurance-checkbox" value="1" id="insurance" name="insurance">
                    <label for="insurance">{l s='Insurance' mod='myparcelbe'}</label>
                </div>
                <div class="insurance-additional-container">
                    <div class="form-group insurance-additional-predefined">
                        <div class="myparcel-radio-container">
                            <input
                                    id="upto100"
                                    type="radio"
                                    value="amount100"
                                    name="insuranceAmount"
                                    class="myparcel-radio"
                                    checked
                            >
                            <label for="upto100">{l s='Up to € 100' mod='myparcelbe'}</label>
                        </div>
                        <div class="myparcel-radio-container">
                            <input id="upto250" type="radio" value="amount250" name="insuranceAmount" class="myparcel-radio">
                            <label for="upto250">{l s='Up to € 250' mod='myparcelbe'}</label>
                        </div>
                        <div class="myparcel-radio-container">
                            <input id="upto500" type="radio" value="amount500" name="insuranceAmount" class="myparcel-radio">
                            <label for="upto500">{l s='Up to € 500' mod='myparcelbe'}</label>
                        </div>
                    </div>
                    {if !$isBE}
                        <div class="form-group insurance-amount-custom">
                            <div class="myparcel-radio-container">
                                <input id="heigherthen500" type="radio" value="-1" class="myparcel-radio" name="insuranceAmount">
                                <label for="heigherthen500">{l s='Higher than € 500' mod='myparcelbe'}</label>
                            </div>
                            <div class="money-input-wrapper">
                                <div class="input-group money-type">
                                    <div class="input-group-addon input-group-prepend">
                                        <span class="input-group-prefix input-group-text">{$currencySign}</span>
                                    </div>
                                    <input
                                            type="text"
                                            id="myparcel-insurance-higher-amount"
                                            name="insurance-amount-custom-value"
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
<div
        class="modal fade"
        id="bulk-export-print"
        tabindex="-1" role="dialog"
        aria-labelledby="bulkExportPrintModalLongTitle"
        aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkExportPrintModalLongTitle">{l s='Export and Print' mod='myparcelbe'}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{$export_print_bulk_action}" method="post" id="export-print-bulk-form">
                    <div class="myparcel-radio-wrapper">
                        <div class="myparcel-radio-container">
                            <input
                                    type="radio"
                                    value="a4"
                                    name="format"
                                    class="myparcel-radio page-format-radio page-format-a4"
                                    id="a4_bulk_export_print"
                                    {if $labelConfiguration.MYPARCELBE_LABEL_SIZE eq 'a4'}checked="checked"{/if}
                            >
                            <label for="a4_bulk_export_print">{l s='A4' mod='myparcelbe'}</label>
                        </div>
                        <div class="myparcel-radio-container">
                            <input
                                    type="radio"
                                    value="a6"
                                    name="format"
                                    class="myparcel-radio page-format-radio page-format-a6"
                                    id="a6_bulk_export_print"
                                    {if $labelConfiguration.MYPARCELBE_LABEL_SIZE eq 'a6'}checked="checked"{/if}
                            >
                            <label for="a6_bulk_export_print">{l s='A6' mod='myparcelbe'}</label>
                        </div>
                    </div>
                    <br>
                    <div class="positions-block">
                        <input
                                id="top-left-bulk-export-print"
                                type="checkbox"
                                value="1"
                                name="position[]"
                                {if 1 / $labelConfiguration.MYPARCELBE_LABEL_POSITION >= 1}checked="checked"{/if}
                        >
                        <label for="top-left-bulk-export-print">{l s='Top-left' mod='myparcelbe'}</label>
                        <br>
                        <input
                                id="top-right-bulk-export-print"
                                type="checkbox"
                                value="2"
                                name="position[]"
                                {if 2 / $labelConfiguration.MYPARCELBE_LABEL_POSITION >= 1}checked="checked"{/if}
                        >
                        <label for="top-right-bulk-export-print">{l s='Top-right' mod='myparcelbe'}</label>
                        <br>
                        <input
                                id="bottom-left-bulk-export-print"
                                type="checkbox"
                                value="3"
                                name="position[]"
                                {if 3 / $labelConfiguration.MYPARCELBE_LABEL_POSITION >= 1}checked="checked"{/if}
                        >
                        <label for="bottom-left-bulk-export-print">{l s='Bottom-left' mod='myparcelbe'}</label>
                        <br>
                        <input
                                id="bottom-right-bulk-export-print"
                                type="checkbox"
                                value="4"
                                name="position[]"
                                {if 4 / $labelConfiguration.MYPARCELBE_LABEL_POSITION >= 1}checked="checked"{/if}
                        >
                        <label for="bottom-right-bulk-export-print">{l s='Bottom-right' mod='myparcelbe'}</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Close' mod='myparcelbe'}</button>
                <button type="submit" id="export-print-bulk-button" class="btn btn-primary">
                    {l s='Export and print' mod='myparcelbe'}
                </button>
            </div>
        </div>
    </div>
</div>
