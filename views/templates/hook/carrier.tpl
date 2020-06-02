<div class="myparcel-delivery-options-wrapper"></div>
<script type="application/javascript">
  var deliverySettingsMP = null;
  {if !empty($delivery_settings)}
    deliverySettingsMP = {$delivery_settings|json_encode nofilter};
  {/if}
</script>
