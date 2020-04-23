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
                    <input class="form-control" id="a4" type="radio" value="a4" name="format">
                    <label for="a4">A4</label>
                    <input class="form-control" id="a6" type="radio" checked value="a6" name="format">
                    <label for="a6">A6</label>
                    <br>
                    <div class="positions-block">
                        <label for="top-left">Top-left</label>
                        <input class="form-control" id="top-left" type="checkbox" value="1" name="position[]">
                        <br>
                        <label for="top-right">Top-right</label>
                        <input class="form-control" id="top-right" type="checkbox" value="2" name="position[]">
                        <br>
                        <label for="bottom-left">Bottom-left</label>
                        <input class="form-control" id="bottom-left" type="checkbox" value="3" name="position[]">
                        <br>
                        <label for="bottom-right">Bottom-right</label>
                        <input class="form-control" id="bottom-right" type="checkbox" value="4" name="position[]">
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
                <label for="labels_amount">Amout of labels</label>
                <input id="labels_amount" name="number" value="1" type="number" min="1" class="form-control">
                <label for="{Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME}">Package type</label>
                <select name="{Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME}" class="custom-select">
                    <option value="1">Packet</option>
                    <option value="2">Mailbox package</option>
                    <option value="3">Letter</option>
                    <option value="4">Digital stamp</option>
                </select>
                <label for="{Gett\MyParcel\Constant::MY_PARCEL_ONLY_RECIPIENT_CONFIGURATION_NAME}">Only to receipient</label>
                <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::MY_PARCEL_ONLY_RECIPIENT_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::MY_PARCEL_ONLY_RECIPIENT_CONFIGURATION_NAME}">

                <label for="{Gett\MyParcel\Constant::MY_PARCEL_AGE_CHECK_CONFIGURATION_NAME}">Age check</label>
                <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::MY_PARCEL_AGE_CHECK_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::MY_PARCEL_AGE_CHECK_CONFIGURATION_NAME}">
                <select name="{Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_FORMAT_CONFIGURATION_NAME}" class="custom-select">
                    <option value="1">Normal</option>
                    <option value="2">Large</option>
                    <option value="3">Automatic</option>
                </select>
                <label for="{Gett\MyParcel\Constant::MY_PARCEL_RETURN_PACKAGE_CONFIGURATION_NAME}">Return package</label>
                <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::MY_PARCEL_RETURN_PACKAGE_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::MY_PARCEL_RETURN_PACKAGE_CONFIGURATION_NAME}">

                <label for="{Gett\MyParcel\Constant::MY_PARCEL_SIGNATURE_REQUIRED_CONFIGURATION_NAME}">Signature</label>
                <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::MY_PARCEL_SIGNATURE_REQUIRED_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::MY_PARCEL_SIGNATURE_REQUIRED_CONFIGURATION_NAME}">

                <label for="{Gett\MyParcel\Constant::MY_PARCEL_INSURANCE_CONFIGURATION_NAME}">Insurnance</label>
                <input type="checkbox" value="1" id="{Gett\MyParcel\Constant::MY_PARCEL_INSURANCE_CONFIGURATION_NAME}" name="{Gett\MyParcel\Constant::MY_PARCEL_INSURANCE_CONFIGURATION_NAME}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id = "add" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).load(function () {

        $('button[data-target="#create"]').click(function(){
            var id = $(this).data('order-id');
            $('#order_id').val(id);
        });

        $('#add').click(function () {
            $.ajax({
                method: "POST",
                url: '{{$action}}',
                data: $('#print-modal :input').serialize()
            }).done((result) => {
                window.location.reload();
            }).fail(() => {

            });
        });



    });

    document.addEventListener("DOMContentLoaded", () => {
        $('button[data-target="#print"]').click(function(){
            var id = $(this).data('label-id');
            $('#id_label').val(id);
        });

        $('#print_button').click(function () {
            $('#print-form').submit();
        });
    });
</script>