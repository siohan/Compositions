<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Cotisations use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$record_id = '';
if(isset($params['record_id']) && $params['record_id'] !='')
{
	$record_id = $params['record_id'];
}
global $themeObject;
$shopping = '<img src="../modules/Cotisations/images/paiement.png" class="systemicon" alt="Réglez" title="Réglez">';
$smarty->assign('shopping', $shopping);
$false = $themeObject->DisplayImage('icons/extra/false.gif', $this->Lang('false'), '', '', 'systemicon');
$smarty->assign('false', $false);
$result= array ();
$query = "SELECT id,ref_action, date_created, montant_paiement, moyen_paiement, statut, commentaires FROM ".cms_db_prefix()."module_paiements_reglements  WHERE ref_action = ?";
$dbresult= $db->Execute($query, array($record_id));
	
	//echo $query;
	$rowarray= array();
	$rowclass = '';
	$cotis_ops = new cotisationsbis();
	//$paiements_ops = cms_utils::get_module('Paiements');
	$paiements_ops = new paiementsbis();
	
		if ($dbresult && $dbresult->RecordCount() > 0)
  		{
    			while ($row= $dbresult->FetchRow())
      			{
				$onerow= new StdClass();
				$onerow->rowclass= $rowclass;
				$onerow->id= $row['id'];
				$onerow->ref_action= $row['ref_action'];
				$onerow->date_created = $row['date_created'];				
				$onerow->montant_paiement = $row['montant_paiement'];
				$onerow->moyen_paiement = $row['moyen_paiement'];
				$onerow->statut = $row['statut'];
				$onerow->commentaires = $row['commentaires'];
				$onerow->edit = $this->CreateLink($id, 'cotisations',$returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array("ref_action"=>$row['ref_action'], "record_id"=>$row['id']));
				$onerow->delete = $this->CreateLink($id, 'cotisations',$returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array("obj"=>"delete_user_cotis","ref_action"=>$row['ref_action']));				
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		
		

echo $this->ProcessTemplate('view_reglements.tpl');


#
# EOF
#
?>