<h3>Ma compo d'équipe</h3>
<div class="alert alert-info">Les joueurs déjà positionnés dans d'autres équipes n'apparaissent pas dans la liste.</div>
{form_start action='fe_add_edit_compos_equipe'}
<input type="hidden" name="record_id" value="{$record_id}">
<input type="hidden" name="ref_action" value="{$ref_action}"/>
<input type="hidden" name="ref_equipe" value="{$ref_equipe}"/>
	
{foreach from=$items key=key item=entry}

	<input type="checkbox" name="genid[{$entry->genid}]" value="{$entry->genid}" {if $entry->participe == "1"}   checked{/if} />  {$entry->joueur}<br />
{/foreach}

<input type="submit" name="submit" value="Envoyer" onClick="Message()"/>
{form_end}
