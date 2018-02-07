
<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
<div class="pageoptions<"><p><span class="pageoptions warning">{$import_from_ping}</span></p></div>
{$phase}
{if $itemcount > 0}

<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>Equipe</th>
		<th>Championnat</th>
		<th>Clt mini (par joueur)</th>
		<th>Clt maxi (pour l'équipe)</th>
		<th colspan="4">Actions</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->libequipe}({$entry->friendlyname})</td>
    <td>{$entry->idepreuve}</td>
	<td>{$entry->clt_mini}</td>
	<td>{$entry->points_maxi}</td>
	<td>{$entry->editlink}</td>
    <td>{$entry->deletelink}</td>
  </tr>
{/foreach}
 </tbody>
</table>
{/if}


