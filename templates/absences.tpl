{$add_edit_absence}
{if $itemcount > 0}

<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>Joueur</th>
		<th>Début absence</th>
		<th>Fin absence</th>
		<th>Motif</th>
		<th colspan="3">Actions</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->joueur}</td>
    <td>{$entry->date_debut|cms_date_format:"%d-%m-%Y"}</td>
	<td>{$entry->date_fin}</td>
	<td>{$entry->motif}</td>
	<td>{$entry->view}</td>
	<td>{$entry->editlink}</td>
    <td>{$entry->delete}</td>
	<td>{$entry->send}</td>
  </tr>
{/foreach}
 </tbody>
</table>
{/if}


