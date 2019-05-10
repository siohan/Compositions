<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
global $themeObject;

$lock = '<img src="../modules/Compositions/images/lock.png" class="systemicon" alt="Déverrouiller" title="Déverrouiller">';
$unlock = '<img src="../modules/Compositions/images/unlock.png" class="systemicon" alt="Verrouiller" title="Verrouiller">';
$relance = '<img src="../modules/Paiements/images/forward-email-16.png" class="systemicon" alt="Envoyer une relance" title="Envoyer une relance">';
$details_facture = '<img src="../modules/Paiements/images/billing.jpg" class="systemicon" alt="Détails de la facture" title="Détails de la facture">';
$smarty->assign('details_facture', $details_facture);
$smarty->assign('add_edit_epreuve',
		$this->CreateLink($id, 'add_edit_epreuve', $returnid,$contents='Ajouter une épreuve'));

$result= array ();
$query = "SELECT id,libelle, description, actif FROM ".cms_db_prefix()."module_compositions_epreuves";
$dbresult= $db->Execute($query);
	
	//echo $query;
	$rowarray= array();
	$rowclass = 'row1';
	//$row = 'row1';
	
		if ($dbresult && $dbresult->RecordCount() > 0)
  		{
    			
			$yesimage = $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('download_poule_results'),'','','systemicon');
			$noimage = $themeObject->DisplayImage('icons/system/stop.gif', $this->Lang('download_poule_results'),'','','systemicon');
			$comp_ops = new compositionsbis;
			while ($row= $dbresult->FetchRow())
      			{
				$actif = $row['actif'];
				$onerow= new StdClass();
				$onerow->rowclass= $rowclass;
				$onerow->id= $row['id'];
				$onerow->libelle= $row['libelle'];
				$onerow->description= $row['description'];
				if($actif == 1)
				{
					$onerow->actif = $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('true'),'','','systemicon');
				}
				else
				{
					$onerow->actif = $this->CreateLink($id, 'actif', $returnid, $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'),'','','systemicon'), array("ref_action"=>$row['ref_action'],"actif"=>"1"),$warn_message='Vous pourrez verrouiller toutes les compos pour cette journée');
				}
				$onerow->edit = $this->CreateLink($id, 'add_edit_epreuve', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'),array("record_id"=>$row['id']) );
				$onerow->delete = $this->CreateLink($id, 'delete', $returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'),array("record_id"=>$row['id']) );
				
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		
		

echo $this->ProcessTemplate('epreuves.tpl');


#
# EOF
#
?>