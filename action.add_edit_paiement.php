<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Cotisations use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
global $themeObject;

if(isset($params['record_id']) && $params['record_id'] !="")
{
		$record_id = $params['record_id'];
}



	$query = "SELECT id,licence, ref_action,module, nom, tarif, actif, statut FROM ".cms_db_prefix()."module_paiements_paiements WHERE ref_action = ?";
	$dbresult = $db->Execute($query, array($record_id));
	$compt = 0;
	while ($dbresult && $row = $dbresult->FetchRow())
	{
		$compt++;
		$id = $row['id'];
		$ref_action = $row['ref_action'];
		$nom = $row['nom'];
		$tarif = $row['tarif'];
	}
	
	
	//on construit le formulaire
	$smarty->assign('formstart',
			    $this->CreateFormStart( $id, 'do_add_edit_paiement', $returnid ) );

		$smarty->assign('record_id',
				$this->CreateInputHidden($id,'record_id',$record_id));
		

	
	
	$smarty->assign('nom',
			$this->CreateInputText($id,'nom',(isset($nom)?$nom:""),50,200));
	$smarty->assign('tarif',
			$this->CreateInputText($id,'tarif',(isset($tarif)?$tarif:""),50,200));			
			
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));


	$smarty->assign('formend',
			$this->CreateFormEnd());
echo $this->ProcessTemplate('add_edit_paiement.tpl');

#
# EOF
#
?>