<style>
<style>
a:link {
  color: orange;
  background-color: transparent;
  text-decoration: none;
}

a:hover {
  color: white;
  background-color: orange;
  text-decoration: underline;
}
a:active {
  color: white;
  background-color: transparent;
  text-decoration: underline;
}
</style>
{if $itemcount > 0}
<h3>{$epreuve}</h3>
<p class="warning">{$message}</p>
<table cellpadding="0" class="pagetable cms_sortable tablesorter" id="articlelist">
 <thead>
 </thead>
 <tbody>

  <tr>
	{foreach from=$items item=entry}
	<td {if $entry->class == 1}style="background-color:orange;"{/if}> {$entry->equipe} ({$entry->nb_players})</td>
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

{/if}


