{$start_form}
<div class="pageoverflow">
	<p class="pagetext">Email du trésorier</p>
	<p class="pageinput">{$admin_email}</p>
</div>
<fieldset>
<legend>Relance des impayés</legend>
<div class="pageoverflow">
	<p class="pagetext">Le sujet du mail</p>
	<p class="pageinput">{$sujet_relance_email}</p>
</div>
<div class="pageoverflow">
	<p class="pagetext">Le corps du mail</p>
	<p class="pageinput">{$relance_email}</p>
</div>
</fieldset>
{$submit}
{$end_form}