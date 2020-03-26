<h3>Ajout/ modification d'une épreuve</h3>
<div class="pageoverflow">
{form_start action='add_edit_epreuve'}
<input type="hidden" name="record_id" value="{$record_id}">
<input type="hidden" name="edition" value="{$edition}">


<div class="pageoverflow">
  <p class="pagetext">Libellé</p>
  <p class="pageinput"><input type="text" name="libelle" value="{$libelle}" size="40"></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Description</p>
  <p class="pageinput"><input type="text" name="description" value="{$description}" size="40"></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Actif</p>
  <p class="pageinput"><select name="actif">{cms_yesno selected=$actif}</select></p>
</div>

<div class="pageoverflow">
    <p class="pagetext">&nbsp;</p>
    <p class="pageinput"><input type="submit" name="submit" value="Envoyer"><input type="submit" name="cancel" value="Annuler"></p>
  </div>
{form_end}
</div>
