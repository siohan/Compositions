<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
<p><a href="{cms_action_url action='add_edit_compo'}">{admin_icon icon='newobject.gif'} Ajouter une composition</a> </p>
{if $itemcount > 0}

<table cellpadding="0" class="pagetable cms_sortable tablesorter" id="articlelist">
 <thead>
	<tr>
		<!--<th>Date</th>-->
		<th>Id</th>
		<th>Championnat</th>
		<th>Journée</th>
		<th>Nb Equipes</th>
		<th>Nb joueurs mini</th>
		<th>Fait</th>
		<th>Actif ?</th>
		<th>Verrou</th>
		<th colspan="6">Action(s)</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<!--<td>{$entry->date_created}</td>-->
	<td> {$entry->id}</td>
	<td>{$entry->championnat}</td>
	<td>{$entry->journee}</td>
	<td>{$entry->equipes_concernees}</td>
	<td>{$entry->nb_players}</td>
	<td>{$entry->pourcentage_remplissage}%</td>
	<td>{$entry->actif}</td>
	<td>{$entry->statut}</td>
	<td>{$entry->emailing}</td>
	<td>{$entry->sms}</td>
	<td>{$entry->view}</td>
	<td>{$entry->edit}</td>
	<td>{$entry->duplicate}</td>
	<td>{$entry->delete}</td>
  </tr>
{/foreach}
 </tbody>
</table>

{/if}

