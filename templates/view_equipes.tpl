{$retour}
{if $itemcount > 0}
<p class="warning">{$message}</p>
<table cellpadding="0" class="pagetable cms_sortable tablesorter" id="articlelist">
 <thead>
 </thead>
 <tbody>

  <tr>
	{foreach from=$items item=entry}
	<td> {$entry->equipe}</td>
	{/foreach}	
  </tr>

 </tbody>
</table>

{/if}
{if $itemcount2 > 0}

<table cellpadding="0" class="pagetable cms_sortable tablesorter" id="articlelist">
 <thead>
 </thead>
 <tbody>
{foreach from=$items2 item=entry}
  <tr>
	<td> {$entry->licence}</td>
  </tr>
	{/foreach}
 </tbody>
</table>
{$delete}    {$modifier}    {$lock}    {$emailing}
{else}
{$ajouter}
{/if}


