<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}&nbsp;</p></div>
<p><a href="{cms_action_url action='add_edit_epreuve'}">{admin_icon icon="newobject.gif"} Ajouter une épreuve</a></p>
{if $itemcount > 0}

<table cellpadding="0" class="pagetable cms_sortable tablesorter" id="articlelist">
 <thead>
	<tr>
		<!--<th>Date</th>-->
		<th>Id</th>
		<th>Libellé</th>
		<th>Description</th>
		<th>Actif</th>
		<th colspan="4">Action(s)</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<!--<td>{$entry->date_created}</td>-->
	<td> {$entry->id}</td>
	<td>{$entry->libelle}</td>
	<td>{$entry->description}</td>
	<td>{$entry->actif}</td>
	<td>{$entry->view}</td>
	<td>{$entry->edit}</td>
	<td>{$entry->delete}</td>
  </tr>
{/foreach}
 </tbody>
</table>

{/if}

