<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
<p>{$add_edit_compo} </p>
{if $itemcount > 0}

<table cellpadding="0" class="pagetable cms_sortable tablesorter" id="articlelist">
 <thead>
	<tr>
		<!--<th>Date</th>-->
		<th>Id</th>
		<th>Championnat</th>
		<th>Nb Equipes</th>
		<th>Journée</th>
		<th>Phase</th>
		<th>Fait</th>
		<th>Brouillon/Propre ?</th>
		<th>Verrou</th>
		<th colspan="4">Action(s)</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<!--<td>{$entry->date_created}</td>-->
	<td> {$entry->id}</td>
	<td>{$entry->championnat}({$entry->idepreuve})</td>
	<td>{$entry->equipes_concernees}</td>
	<td>{$entry->journee}</td>
	<td>{$entry->phase}</td>
	<td>{$entry->pourcentage_remplissage}%</td>
	<td>{$entry->actif}</td>
	<td>{$entry->statut}</td>
	<td>{$entry->manage}</td>
	<td>{$entry->view}</td>
	<td>{$entry->duplicate}</td>
	<td>{$entry->delete}</td>
  </tr>
{/foreach}
 </tbody>
</table>

{/if}

