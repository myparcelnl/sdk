{if version_compare($smarty.const._PS_VERSION_, '1.6.0.0', '<')}
    <table class="table" cellpadding="0" cellspacing="0" style="margin:auto;text-align:center">
        <tbody>
        <tr>
            {if isset($menutabs)}
                {foreach from=$menutabs item=tab}
                    <th>
                        <a id="{$tab.short|escape:'htmlall':'UTF-8'}" href="{$tab.href|escape:'htmlall':'UTF-8'}"
                           {if $tab.active}style="color:black" {else}style="color:grey"{/if}>
                            {$tab.desc|escape:'htmlall':'UTF-8'}
                        </a>
                    </th>
                {/foreach}
            {/if}
        </tr>
        </tbody>
    </table>
    <br/>
{else}
    <nav class="navbar navbar-default" role="navigation">
        <ul class="nav navbar-nav">
            {if isset($menutabs)}
                {foreach from=$menutabs item=tab}
                    <li class="{if $tab.active}active{/if}">
                        <a id="{$tab.short|escape:'htmlall' nofilter}" href="{$tab.href|escape:'htmlall' nofilter}">
                            <span class="icon {$tab.icon|escape:'htmlall' nofilter}"></span>
                            {$tab.short|escape:'htmlall' nofilter}
                        </a>
                    </li>
                {/foreach}
            {/if}
        </ul>
    </nav>
{/if}
