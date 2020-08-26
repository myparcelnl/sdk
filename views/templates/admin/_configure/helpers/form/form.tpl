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
  {elseif $input.type == 'checkbox'}
    {if isset($input.expand)}
      <a class="btn btn-default show_checkbox{if strtolower($input.expand.default) == 'hide'} hidden{/if}" href="#">
        <i class="icon-{$input.expand.show.icon}"></i>
          {$input.expand.show.text}
          {if isset($input.expand.print_total) && $input.expand.print_total > 0}
            <span class="badge">{$input.expand.print_total}</span>
          {/if}
      </a>
      <a class="btn btn-default hide_checkbox{if strtolower($input.expand.default) == 'show'} hidden{/if}" href="#">
        <i class="icon-{$input.expand.hide.icon}"></i>
          {$input.expand.hide.text}
          {if isset($input.expand.print_total) && $input.expand.print_total > 0}
            <span class="badge">{$input.expand.print_total}</span>
          {/if}
      </a>
    {/if}
    {foreach $input.values.query as $value}
      {assign var=id_checkbox value=$input.name|cat:'_'|cat:$value[$input.values.id]}
      {$cutoff_time = $input.cutoff_time[$value[$input.values.id]]}
      <div class="checkbox{if isset($input.expand) && strtolower($input.expand.default) == 'show'} hidden{/if}">
        {strip}
          <label for="{$id_checkbox}">
            <input type="checkbox" name="{$id_checkbox}" id="{$id_checkbox}" class="{if isset($input.class)}{$input.class}{/if}"{if isset($value.val)} value="{$value.val|escape:'html':'UTF-8'}"{/if}{if isset($fields_value[$id_checkbox]) && $fields_value[$id_checkbox]} checked="checked"{/if} />
              {$value[$input.values.name]}
          </label>
        {/strip}
        <div class="input-group col-lg-2 col-md-3 col-sm-4">
          {if isset($cutoff_time.prefix)}
            <span class="input-group-addon">
              {$cutoff_time.prefix}
            </span>
          {/if}
          <input type="text"
                 id="{$cutoff_time.name|escape:'html'}_pseudo"
                 name="{$cutoff_time.name|escape:'html'}_pseudo"
                 class="{if isset($cutoff_time.class)}{$cutoff_time.class|escape:'html'}{/if}"
                 value="{$fields_value[$cutoff_time.name]|escape:'html'}"
                  {if isset($cutoff_time.readonly) && $cutoff_time.readonly} readonly="readonly"{/if}
                  {if isset($cutoff_time.disabled) && $cutoff_time.disabled} disabled="disabled"{/if}
                  {if isset($cutoff_time.required) && $cutoff_time.required} required="required" {/if}
                  {if isset($cutoff_time.placeholder) && $cutoff_time.placeholder} placeholder="{$cutoff_time.placeholder|escape:'html'}"{/if} />
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
              $('#{$cutoff_time.name|escape:'html'}_pseudo').timepicker({
                timeOnly: true,
                timeFormat: 'hh:mm'
              });
            });
          }

            {if version_compare($smarty.const._PS_VERSION_, '1.6.0.0', '>=')}initTimepicker();{/if}
        }());
      </script>
    {/foreach}
  {else}
    {$smarty.block.parent}
  {/if}
{/block}
{block name="after" append}
  <script type="text/javascript">
  (function () {
    function initFormScript() {
      if (typeof $ === 'undefined') {
        return setTimeout(initFormScript, 100);
      }

      $(document).on('change', '.cutoff-time-pseudo', function () {
        var $field = $('#' + $(this).prop('id').replace('_pseudo', ''));
        if ($field.length) {
          $field.val($(this).val());
        }
      });
    }

    {if version_compare($smarty.const._PS_VERSION_, '1.6.0.0', '>=')}initFormScript();{/if}
  }());
  </script>
{/block}