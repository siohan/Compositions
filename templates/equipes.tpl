<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
  $('#selectall').click(function(){
    var v = $(this).attr('checked');
    if( v == 'checked' ) {
      $('.select').attr('checked','checked');
    } else {
      $('.select').removeAttr('checked');
    }
  });
  $('.select').click(function(){
    $('#selectall').removeAttr('checked');
  });
  $('#toggle_filter').click(function(){
    $('#filter_form').toggle();
  });
  {if isset($tablesorter)}
  $('#articlelist').tablesorter({ sortList:{$tablesorter} });
  {/if}
});
//]]>
</script>
<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
<div class="pageoptions<"><p><span class="pageoptions"><a href="{cms_action_url action='add_edit_equipe'}">{admin_icon icon="newobject.gif"}Ajouter une équipe</a></span></p></div>
{if $itemcount > 0}
{$form2start}
<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>Equipe</th>
		<th>Nom court</th>
		<th>Championnat</th>
		<th>Capitaine</th>
		<th>Groupe associé</th>
		<th>Joueurs mini</th>
		<th colspan="2">Actions</th>
		<th><input type="checkbox" id="selectall" name="selectall"></th>
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
<td><input type="checkbox" name="{$actionid}sel[]" value="{$entry->id_equipe}" class="select"></td>
  </tr>
{/foreach}
 </tbody>
</table>
<!-- SELECT DROPDOWN -->
<div class="pageoptions" style="float: right;">
<br/>{$actiondemasse}{$submit_massaction}
  </div>
{$form2end}
{/if}


