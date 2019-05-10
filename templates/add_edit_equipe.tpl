<h3>Ajout / modification d'une équipe</h3>
<div class="pageoverflow">
{$formstart}
{$record_id}

<div class="pageoverflow">
  <p class="pagetext">Libellé de l'équipe</p>
  <p class="pageinput">{$libequipe}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Nom court {cms_help key='nom_court'}</p>
  <p class="pageinput">{$friendlyname}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Championnat</p>
  <p class="pageinput">{$idepreuve}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Groupe associé à cette équipe</p>
  <p class="pageinput">{$liste_id}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Capitaine </p>
  <p class="pageinput">{$capitaine}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Nb de joueurs {cms_help key='nb_joueurs'}</p>
  <p class="pageinput">{$nb_joueurs}</p>
</div>
<div class="pageoverflow">
    <p class="pagetext">&nbsp;</p>
    <p class="pageinput">{$submit}- {$submitasnew} - {$cancel}</p>
  </div>
{$formend}
</div>
