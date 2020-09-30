{extends file="helpers/form/form.tpl"}

{block name="input"}
  {if $input.type == 'time'}
    <div {if version_compare($smarty.const._PS_VERSION_, '1.6.0.0', '>=')}class="row"{/if}>
      <div class="input-group col-lg-2 col-md-3 col-sm-4">
        <input
                type="text"
                id="{$input.name|escape:'html'}"
                name="{$input.name|escape:'html'}"
                class="{if isset($input.class)}{$input.class|escape:'html'}{/if}"
                value="{if isset($fields_value[$input.name])}{$fields_value[$input.name]|escape:'html'}{/if}"
                {if isset($input.readonly) && $input.readonly}readonly="readonly"{/if}
                {if isset($input.disabled) && $input.disabled}disabled="disabled"{/if}
                {if isset($input.required) && $input.required}required="required" {/if}
                {if isset($input.placeholder) && $input.placeholder}placeholder="{$input.placeholder|escape:'html'}"{/if}
        />
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
  {elseif $input.type == 'checkbox' && !empty($input.form_group_class) && $input.form_group_class == 'with-cutoff-time'}
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
            <input
                    type="checkbox"
                    name="{$id_checkbox}"
                    id="{$id_checkbox}"
                    class="{if isset($input.class)}{$input.class}{/if}"
                    {if isset($value.val)} value="{$value.val|escape:'html':'UTF-8'}"{/if}
                    {if isset($fields_value[$id_checkbox]) && $fields_value[$id_checkbox]}checked="checked"{/if}
            />
              {$value[$input.values.name]}
          </label>
        {/strip}
        <div class="input-group col-sm-4">
          {if isset($cutoff_time.prefix)}
            <span class="input-group-addon">
              {$cutoff_time.prefix}
            </span>
          {/if}
          <input
                  type="text"
                  id="{$cutoff_time.name|escape:'html'}_pseudo"
                  name="{$cutoff_time.name|escape:'html'}_pseudo"
                  class="{if isset($cutoff_time.class)}{$cutoff_time.class|escape:'html'}{/if}"
                  value="{if isset($fields_value[$cutoff_time.name])}{$fields_value[$cutoff_time.name]|escape:'html'}{/if}"
                  {if isset($cutoff_time.readonly) && $cutoff_time.readonly}readonly="readonly"{/if}
                  {if isset($cutoff_time.disabled) && $cutoff_time.disabled}disabled="disabled"{/if}
                  {if isset($cutoff_time.required) && $cutoff_time.required}required="required" {/if}
                  {if isset($cutoff_time.placeholder) && $cutoff_time.placeholder}placeholder="{$cutoff_time.placeholder|escape:'html'}"{/if}
          />
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
  {elseif $input.type == 'cutoffexceptions'}
    <input
            type="hidden"
            id="{$input.name|escape:'html'}" name="{$input.name|escape:'html'}"
            value="{if isset($fields_value[$input.name])}{$fields_value[$input.name]|escape:'html'}{/if}"
    />
    <div class="datepicker-row">
      <div id="datepicker_{$input.name|escape:'html'}" class="datepicker-calendar"></div>
      <div class="datepicker-exceptions clearfix">
        <div id="{$input.name|escape:'html'}_datepanel" class="panel">
          <div class="panel-heading">
            <i class="icon icon-calendar"></i> <span id="{$input.name|escape:'html'}_datetitle"></span>
          </div>
          <div class="date-warning" style="display:none">
            <div class="alert alert-info">{l s='Select a date in the future to configure its cutoff time' mod='myparcelbe'}</div>
          </div>
          <div class="panel-body">
            <div class="btn-group" role="group">
              <button type="button" id="{$input.name|escape:'html'}-nodispatch-btn" class="btn btn-default">
                <i class="icon-times"></i> {l s='No dispatch' mod='myparcelbe'}
              </button>
              <button type="button" id="{$input.name|escape:'html'}-otherdispatch-btn" class="btn btn-default">
                <i  class="icon-clock-o"></i> {l s='Different cut-off time' mod='myparcelbe'}
              </button>
              <div id="{$input.name|escape:'html'}-dispatch-btn" class="btn btn-success">
                <i class="icon-check"></i> {l s='Normal cut-off time' mod='myparcelbe'}
              </div>
            </div>
            <div class="form-inline well" style="margin-top: 5px">
              <div class="form-group">
                <label for="{$input.name|escape:'html'}-cutoff">{l s='Cut-off time' mod='myparcelbe'}: </label>
                <div class="input-group">
                  <input
                          type="text"
                          id="{$input.name|escape:'html'}-cutoff"
                          name="{$input.name|escape:'html'}-cutoff"
                          class="{if isset($input.class)}{$input.class|escape:'html'}{/if} form-control"
                          {if isset($input.readonly) && $input.readonly}readonly="readonly"{/if}
                          {if isset($input.disabled) && $input.disabled}disabled="disabled"{/if}
                          {if isset($input.required) && $input.required}required="required" {/if}
                          {if isset($input.placeholder) && $input.placeholder}placeholder="{$input.placeholder|escape:'html'}"{/if}
                  />
                  <span class="input-group-addon">
                  <i class="icon-clock-o"></i>
                </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      (function () {
        function {$input.name|escape:'html'}highlightDays(date) {
          var dates = JSON.parse($('#{$input.name|escape:'html'}').val());
          for (var i = 0; i < Object.keys(dates).length; i++) {
            var item = dates[Object.keys(dates)[i]];
            var formattedDate = Object.keys(dates)[i].split('-');
            if (new Date(formattedDate[2], formattedDate[1] - 1, formattedDate[0]).toISOString().slice(0, 10) == date.toISOString().slice(0, 10)) {
              if (item.cutoff) {
                return [true, 'ui-state-warning', ''];
              } else {
                return [true, 'ui-state-danger', ''];
              }

            }
          }
          return [true, ''];
        }

        function {$input.name|escape:'javascript'}dateSelect(date) {
          if (moment().format('YYYY-MM-DD') > moment(date, 'DD-MM-YYYY').format('YYYY-MM-DD')) {
            $('#{$input.name|escape:'html'}_datepanel').find('.panel-body').hide();
            $('#{$input.name|escape:'html'}_datepanel').find('.date-warning').show();
          } else {
            $('#{$input.name|escape:'html'}_datepanel').find('.panel-body').show();
            $('#{$input.name|escape:'html'}_datepanel').find('.date-warning').hide();
            var dates = JSON.parse($('#{$input.name|escape:'html'}').val());
            if (!!dates[date]) {
              var item = dates[date];
              if (item.cutoff) {
                  {$input.name|escape:'javascript'}setOtherDispatch(item.cutoff);
              } else {
                  {$input.name|escape:'javascript'}setNoDispatch();
              }
            } else {
                {$input.name|escape:'javascript'}setDispatch();
            }
          }

          $('#{$input.name|escape:'html'}_datetitle').text(moment(date, 'DD-MM-YYYY').format('DD MMMM YYYY'));
        }

        window.setDate = {$input.name|escape:'javascript'}dateSelect;

        function {$input.name|escape:'javascript'}setDispatch() {
          $('#{$input.name|escape:'javascript'}-nodispatch-btn').addClass('btn-default').removeClass('btn-danger');
          $('#{$input.name|escape:'javascript'}-otherdispatch-btn').addClass('btn-default').removeClass('btn-warning');
          $('#{$input.name|escape:'javascript'}-dispatch-btn').addClass('btn-success').removeClass('btn-default');
          $('#{$input.name|escape:'javascript'}-cutoff').val('');
        }

        function {$input.name|escape:'javascript'}setOtherDispatch(cutoff) {
          $('#{$input.name|escape:'javascript'}-nodispatch-btn').addClass('btn-default').removeClass('btn-danger');
          $('#{$input.name|escape:'javascript'}-otherdispatch-btn').addClass('btn-warning').removeClass('btn-default');
          $('#{$input.name|escape:'javascript'}-dispatch-btn').addClass('btn-default').removeClass('btn-success');
          $('#{$input.name|escape:'javascript'}-cutoff').val(cutoff);
        }

        function {$input.name|escape:'javascript'}setNoDispatch() {
          $('#{$input.name|escape:'javascript'}-nodispatch-btn').addClass('btn-danger').removeClass('btn-default');
          $('#{$input.name|escape:'javascript'}-otherdispatch-btn').addClass('btn-default').removeClass('btn-warning');
          $('#{$input.name|escape:'javascript'}-dispatch-btn').addClass('btn-default').removeClass('btn-success');
          $('#{$input.name|escape:'javascript'}-cutoff').val('');
        }

        function {$input.name|escape:'javascript'}addDate(date) {
          var dates = JSON.parse($('#{$input.name|escape:'html'}').val());
          dates[date] = {
            "nodispatch": true
          };
          $('#{$input.name|escape:'html'}').val(JSON.stringify(dates));
        }

        function {$input.name|escape:'javascript'}addCutOff(date, cutoff) {
          var dates = JSON.parse($('#{$input.name|escape:'html'}').val());
          dates[date] = {
            "nodispatch": true,
            "cutoff": cutoff
          };
          $('#{$input.name|escape:'html'}').val(JSON.stringify(dates));
        }

        function {$input.name|escape:'javascript'}removeDate(date) {
          var dates = JSON.parse($('#{$input.name|escape:'html'}').val());
          delete dates[date];
          $('#{$input.name|escape:'html'}').val(JSON.stringify(dates));
        }

        $(document).ready(function () {
          $('#datepicker_{$input.name|escape:'javascript'}').datepicker({
            dateFormat: 'dd-mm-yy',
            beforeShowDay: {$input.name|escape:'javascript'}highlightDays,
            minDate: 0,
            onSelect: {$input.name|escape:'javascript'}dateSelect
          });
          $('#{$input.name|escape:'javascript'}-cutoff').timepicker({
            timeOnly: true,
            timeFormat: 'hh:mm'
          });
          $('#{$input.name|escape:'javascript'}-dispatch-btn').click(function () {
              {$input.name|escape:'javascript'}removeDate($('#datepicker_{$input.name|escape:'javascript'}').val());
              {$input.name|escape:'javascript'}setDispatch();
          });
          $('#{$input.name|escape:'javascript'}-otherdispatch-btn').click(function () {
            if ($('#{$input.name|escape:'javascript'}-cutoff').val()) {
                {$input.name|escape:'javascript'}removeDate($('#datepicker_{$input.name|escape:'javascript'}').val());
                {$input.name|escape:'javascript'}addCutOff(
                  $('#datepicker_{$input.name|escape:'javascript'}').val(),
                  $('#{$input.name|escape:'javascript'}-cutoff').val()
                );
            }
              {$input.name|escape:'javascript'}setOtherDispatch($('#{$input.name|escape:'javascript'}-cutoff').val());
          });
          $('#{$input.name|escape:'javascript'}-cutoff').change(function () {
            if ($(this).val()) {
                {$input.name|escape:'javascript'}removeDate($('#datepicker_{$input.name|escape:'javascript'}').val());
                {$input.name|escape:'javascript'}addCutOff(
                  $('#datepicker_{$input.name|escape:'javascript'}').val(),
                  $('#{$input.name|escape:'javascript'}-cutoff').val()
                );
                {$input.name|escape:'javascript'}setOtherDispatch($(this).val());
            }
          });
          $('#{$input.name|escape:'javascript'}-nodispatch-btn').click(function () {
              {$input.name|escape:'javascript'}removeDate($('#datepicker_{$input.name|escape:'javascript'}').val());
              {$input.name|escape:'javascript'}addDate($('#datepicker_{$input.name|escape:'javascript'}').val());
              {$input.name|escape:'javascript'}setNoDispatch();
          });
          var current_date = new Date($('#datepicker_{$input.name|escape:'javascript'}').datepicker('getDate')),
            yr = current_date.getFullYear(),
            month = (current_date.getMonth() + 1) < 10 ? '0' + (current_date.getMonth() + 1) : (current_date.getMonth() + 1),
            day = current_date.getDate() < 10 ? '0' + current_date.getDate() : current_date.getDate(),
            new_current_date = day + '-' + month + '-' + yr;
            {$input.name|escape:'javascript'}dateSelect(new_current_date);
        });
      }());
    </script>
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