{extends file="helpers/form/form.tpl"}

{block name="input"}
  {if $input.type == 'time'}
    <div {if version_compare($smarty.const._PS_VERSION_, '1.6.0.0', '>=')}class="row"{/if}>
      <div class="input-group col-lg-2 col-md-3 col-sm-4">
        <input type="text"
               id="{$input.name|escape:'html'}"
               name="{$input.name|escape:'html'}"
               class="{if isset($input.class)}{$input.class|escape:'html'}{/if}"
               value="{$fields_value[$input.name]|escape:'html'}"
                {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                {if isset($input.required) && $input.required} required="required" {/if}
                {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder|escape:'html'}"{/if} />
        <span class="input-group-addon">
          <i class="icon-clock-o"></i>
        </span>
      </div>
    </div>
    <script type="text/javascript">
      (function () {
        function initTimepicker() {
          if (typeof $ === 'undefined') {
            return setTimeout(initTimePicker, 100);
          }

          $(document).ready(function () {ldelim}
            $('#{$input.name|escape:'html'}').timepicker({
              timeOnly: true,
              timeFormat: 'hh:mm'
              });
          });
        }

        {if version_compare($smarty.const._PS_VERSION_, '1.6.0.0', '>=')}initTimepicker();{/if}
      }());
    </script>
  {else}
    {$smarty.block.parent}
  {/if}
{/block}
