{*<pre>{$rowarray|var_dump}</pre>*}
{**}<div class="pageoverflow">
	{$retour}
{$formstart}
{$ref_action}
{$ref_equipe}
{foreach from=$rowarray key=key item=entry}
<div class="pageoverflow">
    <p class="pageinput"><input type="checkbox"  name="m1_licence[{$key}]" id="m1_licence[{$key}]" {if $entry['participe'] ==1}checked='checked' {/if} value = '1'>{$entry['name']}</p>
  </div>
{/foreach}
  <div class="pageoverflow">
    <p class="pagetext">&nbsp;</p>
    <p class="pageinput">{$submit}{$cancel}</p>
  </div>
{$formend}
</div>
{**}