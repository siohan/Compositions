<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
{if isset($formstart) }
<fieldset>
  <legend>Filtres</legend>
  {$formstart}
  <div class="pageoverflow">
	<p class="pagetext">Compétition</p>
    <p class="pageinput">{$idepreuve} </p>
	<p class="pagetext">Phase</p>
    <p class="pageinput">{$phase} </p>
    <p class="pageinput">{$submitfilter}{$hidden|default:''}</p>
  </div>
  {$formend}
</fieldset>
{/if}
{if $itemcount > 0}

<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>ID</th>
		<th>Joueur</th>
		<th>J1</th>
		<th>J2</th>
		<th>J3</th>
		<th>J4</th>
		<th>J5</th>
		<th>J6</th>
		<th>J7</th>
		<th>Phase</th>
		<th>Saison</th>
		<th colspan="4">Actions</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->id}</td>
	<td>{$entry->licence}</td>
	<td>{$entry->J1}</td>	
	<td>{$entry->J2}</td>
	<td>{$entry->J3}</td>
	<td>{$entry->J4}</td>
	<td>{$entry->J5}</td>
	<td>{$entry->J6}</td>
	<td>{$entry->J7}</td>
	<td>{$entry->phase}</td>
	<td>{$entry->saison}</td>
	<td>{$entry->editlink}</td>
    <td>{$entry->deletelink}</td>
  </tr>
{/foreach}
 </tbody>
</table>
{/if}


