{if !empty($enableDeliveryOptions)}
<div class="myparcel-delivery-options-wrapper"></div>
{/if}
<script type="application/javascript">
  var deliverySettingsMP = null;
  {if !empty($delivery_settings)}
    deliverySettingsMP = {$delivery_settings|json_encode nofilter};
  {/if}
  {if !empty($carrier)}
  window.addEventListener('load', () => {
    setTimeout(function () {
      $('label[for="delivery_option_{$carrier.id}"] .carrier-price').html('{$shipping_cost}');
    }, 100);
  });
  {/if}
</script>
