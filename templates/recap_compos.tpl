
{if $itemcount > 0}
<h3>{$epreuve}</h3>
<table cellpadding="0" class="pagetable cms_sortable tablesorter" id="articlelist">
 <thead>
 </thead>
 <tbody>
 <tr>
	{foreach from=$items item=entry}
	<td> {$entry->equipe}</td>
	{if $itemcount2 > 0}

	<table cellpadding="0" class="pagetable cms_sortable tablesorter" id="articlelist">
	 <thead>
	 </thead>
	 <tbody>
	{foreach from=$items2 item=entry2}
	  <tr>
		<td> {$entry2->licence}</td>
	  </tr>
		{/foreach}
	 </tbody>
	</table>
	{/if}
	{/foreach}	
  </tr>

 </tbody>
</table>

{/if}



