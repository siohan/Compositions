<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Cotisations use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
global $themeObject;


$gp_ops = new groups;
//include_once(dirname(__FILE__).'/lib/class.cotisationsbis.php');
//use assocotiz;
$cotis_ops = new cotisationsbis();
$result= array ();
$query = "SELECT id, nom, description, tarif,actif, groupe FROM ".cms_db_prefix()."module_cotisations_types_cotisations";
$dbresult= $db->Execute($query);
	
	//echo $query;
	$rowarray= array();
	$rowclass = '';
	
	
		if ($dbresult && $dbresult->RecordCount() > 0)
  		{
    			while ($row= $dbresult->FetchRow())
      			{
				$onerow= new StdClass();
				$onerow->rowclass= $rowclass;

				//les champs disponibles : 
				$actif = $row['actif'];
				$onerow->id= $row['id'];
				$nb = $cotis_ops->count_users_in_group($row['id']);
				$nb_total = $gp_ops->count_users_in_group($row['groupe']);
				$onerow->nom = $row['nom'];
				$onerow->description = $row['description'];
				$details = $gp_ops->details_groupe($row['groupe']);
				$nom_gp = $details['nom'];
				$onerow->groupe = $nom_gp;
				$onerow->nb = $nb;
				$onerow->nb_total = $nb_total;
		
				if($actif == 1)
				{
					$onerow->actif = $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('true'), '', '', 'systemicon');
					$onerow->add_users = $this->CreateLink($id, 'assign_users', $returnid, $themeObject->DisplayImage('icons/system/groupassign.gif', $this->Lang('add'), '', '', 'systemicon'), array("record_id"=>$row['id']));
				}
				else
				{
					$onerow->actif = $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('delete'), '', '', 'systemicon');
				}
				
				$onerow->tarif = $row['tarif'];
				$onerow->editlink= $this->CreateLink($id, 'add_edit_types_cotis', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('record_id'=>$row['id']));
				if($nb == 0)
				{
					//$onerow->deletelink = $this->CreateLink($id, 'cotisations', $returnid,$themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array('obj'=>'delete_cotis','record_id'=>$row['id']));
					$onerow->viewlink = $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'), '', '', 'systemicon');
				}
				else
				{
					$onerow->viewlink = $this->CreateLink($id, 'view_cotis_members', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view'), '', '', 'systemicon'), array("record_id"=>$row['id']));
				}
				
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		
		

echo $this->ProcessTemplate('types_cotis.tpl');


#
# EOF
#
?>