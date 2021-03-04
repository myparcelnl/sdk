{if !empty($enableDeliveryOptions)}
<div class="myparcel-delivery-options-wrapper"></div>
{/if}
<script type="application/javascript">
  var deliverySettingsMP = null;
  {if !empty($delivery_settings)}
    deliverySettingsMP = {$delivery_settings|json_encode nofilter};
  {/if}
  {if !empty($carrier)}
    setTimeout(function() {
      $('label[for="delivery_option_{$carrier.id}"] .carrier-price').html('{$shipping_cost}');
    }, 1000);
  {/if}
</script>
