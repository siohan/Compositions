<h3>Ajout / Modification d'une composition</h3>
{form_start}
<div class="c_full cf">
  <input type="submit" name="submit" value="Envoyer"/>
  {if $edit > 0}
  <input type="submit" name="apply" value="Modifier"/>
  {/if}
  <input type="submit" name="cancel" value="Annuler" formnovalidate/>
</div>
<input type="hidden" name="record_id" value="{$record_id}" />
<input type="hidden" name="edit" value="{$edit}" />
<input type="hidden" name="date_created" value="{$date_created}" />
<input type="hidden" name"statut" value="{$statut}" />
<div class="c_full cf">
	<label class="grid_3">Journée</label>
	<div class="grid_8">
  		<select name="journee">{html_options options=$liste_journees selected=$journee}</select>
	</div>
</div>
<div class="c_full cf">
	<label class="grid_3">Epreuve</label>
	<div class="grid_8">
  		<select name="idepreuve">{html_options options=$liste_epreuves_equipes selected=$idepreuve}</select>
	</div>
</div>
<div class="c_full cf">
	<label class="grid_3">Actif</label>
	<div class="grid_8">
		<select name="actif">{cms_yesno selected=$actif}</select>{cms_help key='help_actif' title='Actif/Inactif'}
	</div>
</div>
<div class="c_full cf">
	<label class="grid_3">Date limite de réponse</label>
	<div class="grid_8">
		{html_select_date start_year='2019' end_year='+20' prefix='limite_' time=$date_limite}@ {html_select_time time=$date_limite prefix='limite_'}{cms_help key='help_date_limite' title='Date limite de réception des réponses'}
	</div>
</div>

{form_end}

