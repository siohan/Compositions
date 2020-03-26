{form_start action='admin_emails_tab'}
<fieldset>
	<legend>Réglages principaux</legend>
<div class="pageoverflow">
	<p class="information">Sans alias, le module ne fonctionnera pas correctement.</p>
	<p class="pagetext">Alias de la page des compositions</p>
	<input type="text" name="pageid_compositions" value="{$pageid_compositions}">
</div>
<div class="pageoverflow">
	<p class="pagetext">Utiliser le module Messages pour stocker les emails ?</p>
	<select name="use_messages">{cms_yesno selected=$use_messages}</select>
</div>
</fieldset>
<fieldset>
<legend>Réglages des emails et SMS</legend>
<div class="pageoverflow">
	<p class="pagetext">Expéditeur des SMS</p>
	<input type="text" name="sms_sender" value="{$sms_sender}" size="40">
</div>
<div class="pageoverflow">
	<p class="pagetext">Email du gestionnaire des compos (sera aussi utilisée comme adresse de retour)</p>
	<input type="text" name="admin_email" value="{$admin_email}" size="40">
</div><div class="pageoverflow">
	<p class="pagetext">Le sujet du mail</p>
	<input type="text" name="sujet_relance_email" value="{$sujet_relance_email}" size="40">
</div>
<div class="information">
	<p class="pagetext">Le corps du mail</p><p>Le corps originel du mail se situe dans le répertoire "templates" du module sous le nom "email_compos_equipes.tpl". Si vous le modifiez pour le personnaliser, rendez-vous dans les répertoires assets/module_custom et créez les répertoires suivants : Compositions/templates pour obtenir le chemin suivant : assets/module_custom/Compositions/templates/email_compos_equipes.tpl, ceci afin de ne pas perdre votre personnalisation lors d'une mise à jour du module</p>
</div>
</fieldset>
<input type="submit" name="submit" value="Envoyer">
<input type="submit" name="cancel" value="Annuler">
{form_end}