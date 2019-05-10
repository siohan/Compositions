
<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
<div class="pageoptions<"><p><span class="pageoptions">{$add_team}</span></p></div>
{if $itemcount > 0}

<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>Equipe</th>
		<th>Nom court</th>
		<th>Championnat</th>
		<th>Capitaine</th>
		<th>Groupe associé</th>
		<th>Joueurs mini</th>
		<th colspan="4">Actions</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->libequipe}</td>
	<td>{$entry->friendlyname}</td>	
    <td>{$entry->idepreuve}</td>
	<td>{$entry->capitaine}</td>
	<td>{$entry->liste_id}</td>
	<td>{$entry->nb_joueurs}</td>
	<td>{$entry->editlink}</td>
    <td>{$entry->delete}</td>
  </tr>
{/foreach}
 </tbody>
</table>
{/if}


