<h3>Ajout / modification d'une équipe</h3>
<div class="pageoverflow">
{form_start action='add_edit_equipe'}
<input type="hidden" name="record_id" value="{$record_id}">
<input type="hidden" name="edit" value="{$edit}">
<div class="pageoverflow">
  <p class="pagetext">Libellé de l'équipe</p>
  <p class="pageinput"><input type="text" name="libequipe" value="{$libequipe}" size="40"></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Nom court {cms_help key='nom_court'}</p>
  <p class="pageinput"><input type="text" name="friendlyname" value="{$friendlyname}"></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Championnat</p>
  <p class="pageinput"><select name="idepreuve">{html_options options=$liste_epreuves selected=$idepreuve}</select></p>
</div>
<div class="pageoverflow"> 
  <p class="pagetext">Groupe associé à cette équipe</p>
  <p class="pageinput"><select name="liste_id">{html_options options=$liste_groupes selected=$liste_id}</select></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Capitaine </p>
  <p class="pageinput"><select name="capitaine">{html_options options=$liste_adherents selected=$capitaine}</select></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Nb de joueurs {cms_help key='nb_joueurs'}</p>
  <p class="pageinput"><input type="text" name="nb_joueurs" value="{$nb_joueurs}" ></p>
</div>
<div class="pageoverflow">
    <p class="pagetext">&nbsp;</p>
    <p class="pageinput"><input type="submit" name="submit" value="Envoyer">
	<input type="submit" name="cancel" value="Annuler"></p>
  </div>
{form_end}
</div>
