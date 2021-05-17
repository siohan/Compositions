<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Cotisations use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$genid = '';
if(isset($params['genid']) && $params['genid'] !='')
{
	$genid = $params['genid'];
}
global $themeObject;
$cotis_ops = new cotisationsbis;
$shopping = '<img src="../assets/modules/Cotisations/images/paiement.png" class="systemicon" alt="Réglez" title="Réglez">';
$smarty->assign('shopping', $shopping);
$false = $themeObject->DisplayImage('icons/extra/false.gif', $this->Lang('false'), '', '', 'systemicon');
$smarty->assign('false', $false);
$result= array ();
$query = "SELECT CONCAT_WS(' ',adh.nom, adh.prenom) AS joueur, adh.licence,adh.genid, be.id_cotisation,be.ref_action, be.reglement FROM ".cms_db_prefix()."module_adherents_adherents AS adh , ".cms_db_prefix()."module_cotisations_belongs AS be  WHERE adh.genid = be.genid AND adh.genid = ?";
$dbresult= $db->Execute($query, array($genid));
	
	$rowarray= array();
	$rowclass = '';
	
		if ($dbresult && $dbresult->RecordCount() > 0)
  		{
    			while ($row= $dbresult->FetchRow())
      			{
				$onerow= new StdClass();
				$onerow->rowclass= $rowclass;
				$smarty->assign('joueur',$row['joueur']);
				$genid = $row['genid'];
				$onerow->licence= $row['genid'];
				$onerow->ref_action= $row['ref_action'];
				$onerow->is_paid = $row['reglement'];
				$tableau = $cotis_ops->types_cotisations($row['id_cotisation']);
				$onerow->nom = $tableau['nom'];				
				$onerow->montant_total = $tableau['tarif'];
				if($this->CheckPermission('Cotisations delete'))
				{
					$onerow->delete = $this->CreateLink($id, 'cotisations',$returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array("obj"=>"delete_user_cotis","ref_action"=>$row['ref_action']));
				}
				else
				{
					$onerow->delete ='';
				}
								
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		
		

echo $this->ProcessTemplate('cotis_adherent.tpl');


#
# EOF
#
?>