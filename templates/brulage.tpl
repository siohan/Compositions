
{if $itemcount > 0}
<h4>Membres déjà utilisés dans une autre équipe</h4>
<table style="width: 50%;">
 <thead>
	<tr>
		<th>Action</th>
		<th>Joueur</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr>
	<td>{$entry->delete_joueur} </td>	
    <td>{$entry->joueur}</td>
  </tr>
{/foreach}
 </tbody>
</table>
{/if}


