<div class="alert alert-success">{$final_message}</div>
{if $itemcount > 0}
<h3>Journée N° {$journee} / {$friendlyname} / {$epreuve}</h3>
<table cellpadding="0" class="pagetable cms_sortable tablesorter" id="articlelist">
 <thead>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr>
	<td> {$entry->licence}</td>
  </tr>
	{/foreach}
 </tbody>
</table>
 {if false == $locked}<a href="{module_action_url action='fe_add_edit_compos_equipe' edit=1 ref_action=$ref_action ref_equipe=$ref_equipe record_id=$record_id}">Modifier ma compo</a> |<a href="{module_action_url action='notify' ref_action=$ref_action ref_equipe=$ref_equipe genid=$record_id}">Notifier</a> |  <a href="{module_action_url action='recap_compos' ref_action=$ref_action}">Voir les autres compos</a><p>En cliquant sur "Notifier", tu verrouilles ta compo et préviens le référent</p>{/if}
{/if}


