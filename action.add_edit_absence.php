<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
global $themeObject;
debug_display($params, 'Parameters');
$ping = new Ping;
$adh_ops = new adherents_spid;
$liste_adherents = $adh_ops->liste_adherents();

$saison_en_cours = $ping->GetPreference('saison_en_cours');
if(isset($params['record_id']) && $params['record_id'] !="")
{
		$record_id = $params['record_id'];
		$edit = 1;
		$query = "SELECT id, licence, date_debut, date_fin, motif FROM ".cms_db_prefix()."module_compositions_absences WHERE id = ?";
		$dbresult = $db->Execute($query, array($record_id));
		$compt = 0;
		while ($dbresult && $row = $dbresult->FetchRow())
		{
			$compt++;
			$id = $row['id'];
			$licence = $row['licence'];
			$date_debut = $row['date_debut'];
			$date_fin = $row['date_fin'];
			$motif = $row['motif'];
		}
}

if(isset($licence))	
{
	$key_licence = array_values($liste_adherents);//$index_paiement = $paiement;
	//var_dump($key_statut_commande);
	$key2_licence = array_search($licence,$key_licence);
	//var_dump($key2_statut_commande);
}
else
{
	$key2_licence = 0;
	$licence = 0;
}

	$OuiNon = array("Non"=>"0", "Oui"=>"1");//on construit le formulaire
	$smarty->assign('formstart',
			    $this->CreateFormStart( $id, 'do_add_edit_absence', $returnid ) );

	
	$smarty->assign('record_id',$this->CreateInputHidden($id,'record_id',$record_id));		

	$smarty->assign('licence',
			$this->CreateInputDropdown($id,'licence',$liste_adherents,$selectedIndex=$key2_licence,$selectedvalue=$licence));
	$smarty->assign('date_debut',
			$this->CreateInputDate($id,'date_debut',(isset($date_debut)?$date_debut:"")));
							
	$smarty->assign('date_fin',
			$this->CreateInputDate($id,'date_fin',(isset($date_fin)?$date_fin:"")));
	$smarty->assign('motif',
			$this->CreateInputText($id,'motif',(isset($motif)?$motif:"0"), 30, 150));
	$smarty->assign('submitasnew',
				$this->CreateInputSubmit($id, 'submitasnew', $this->Lang('add'), 'class="button"'));	
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('edit'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));


	$smarty->assign('formend',
			$this->CreateFormEnd());
echo $this->ProcessTemplate('add_edit_absence.tpl');

#
# EOF
#
?>