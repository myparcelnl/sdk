<!-- Modal -->
<div class="modal fade" id="print" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{$download_action}" method="post" id = "print-form" >
                    <input type="hidden" name="label_id" id="id_label">
                    <div class="myparcel-radio-wrapper">
                        <div class="myparcel-radio-container">
                            <input id="a4" type="radio" value="a4" name="format" class="myparcel-radio">
                            <label for="a4">A4</label>
                        </div>
                        <div class="myparcel-radio-container">
                            <input id="a6" type="radio" checked value="a6" name="format" class="myparcel-radio">
                            <label for="a6">A6</label>
                        </div>
                    </div>
                    <br>
                    <div class="positions-block">
                        <input id="top-left" type="checkbox" value="1" name="position[]">
                        <label for="top-left">Top-left</label>
                        <br>
                        <input id="top-right" type="checkbox" value="2" name="position[]">
                        <label for="top-right">Top-right</label>
                        <br>
                        <input id="bottom-left" type="checkbox" value="3" name="position[]">
                        <label for="bottom-left">Bottom-left</label>
                        <br>
                        <input id="bottom-right" type="checkbox" value="4" name="position[]">
                        <label for="bottom-right">Bottom-right</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id = "print_button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="bulk-print" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{$print_bulk_action}" method="post" id="print-bulk-form" >
                    <div class="myparcel-radio-wrapper">
                        <div class="myparcel-radio-container">
                            <input id="a4" type="radio" value="a4" name="format" class="myparcel-radio">
                            <label for="a4">A4</label>
                        </div>
                        <div class="myparcel-radio-container">
                            <input id="a6" type="radio" checked value="a6" name="format" class="myparcel-radio">
                            <label for="a6">A6</label>
                        </div>
                    </div>
                    <br>
                    <div class="positions-block">
                        <input id="top-left" type="checkbox" value="1" name="position[]">
                        <label for="top-left">Top-left</label>
                        <br>
                        <input id="top-right" type="checkbox" value="2" name="position[]">
                        <label for="top-right">Top-right</label>
                        <br>
                        <input id="bottom-left" type="checkbox" value="3" name="position[]">
                        <label for="bottom-left">Bottom-left</label>
                        <br>
                        <input id="bottom-right" type="checkbox" value="4" name="position[]">
                        <label for="bottom-right">Bottom-right</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" id="print-bulk-button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="print-modal">
                <input type="hidden" id="order_id" name="create_label[order_ids][]">
                <div class="form-group">
                    <input id="labels_amount" name="number" value="1" type="number" min="1" class="form-control">
                    <label for="labels_amount">Amout of labels</label>
                </div>

                <div class="form-group">
                    <label for="{Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME}">Package type</label>
                    <select name="{Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME}"  id="package-type" class="custom-select">
                        <option value="1">Packet</option>
                        <option value="2">Mailbox package</option>
                        <option value="3">Letter</option>
                        <option value="4">Digital stamp</option>
                    </select>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::MY_PARCEL_ONLY_RECIPIENT_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::MY_PARCEL_ONLY_RECIPIENT_CONFIGURATION_NAME}">
                    <label for="{Gett\MyParcel\Constant::MY_PARCEL_ONLY_RECIPIENT_CONFIGURATION_NAME}">Only to receipient</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::MY_PARCEL_AGE_CHECK_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::MY_PARCEL_AGE_CHECK_CONFIGURATION_NAME}">
                    <label for="{Gett\MyParcel\Constant::MY_PARCEL_AGE_CHECK_CONFIGURATION_NAME}">Age check</label>
                </div>

                <div class="form-group">
                    <select name="{Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_FORMAT_CONFIGURATION_NAME}" class="custom-select">
                        <option value="1">Normal</option>
                        <option value="2">Large</option>
                        <option value="3">Automatic</option>
                    </select>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::MY_PARCEL_RETURN_PACKAGE_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::MY_PARCEL_RETURN_PACKAGE_CONFIGURATION_NAME}">
                    <label for="{Gett\MyParcel\Constant::MY_PARCEL_RETURN_PACKAGE_CONFIGURATION_NAME}">Return package</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::MY_PARCEL_SIGNATURE_REQUIRED_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::MY_PARCEL_SIGNATURE_REQUIRED_CONFIGURATION_NAME}">
                    <label for="{Gett\MyParcel\Constant::MY_PARCEL_SIGNATURE_REQUIRED_CONFIGURATION_NAME}">Signature</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::MY_PARCEL_INSURANCE_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::MY_PARCEL_INSURANCE_CONFIGURATION_NAME}">
                    <label for="{Gett\MyParcel\Constant::MY_PARCEL_INSURANCE_CONFIGURATION_NAME}">Insurnance</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id = "add" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>