<?php
if( !isset($gCms) ) exit;
if (!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}

$db =& $this->GetDb();
global $themeObject;
$idepreuve= '';
//debug_display($params, 'Parameters');

$adh_ops = new Asso_adherents;
$gp_ops = new groups;
$smarty ->assign('add_team', $this->CreateLink($id, 'add_edit_equipe', $returnid, $contents='Ajouter' ));
$query = "SELECT id, libequipe, friendlyname,capitaine, idepreuve,nb_joueurs, liste_id FROM ".cms_db_prefix()."module_compositions_equipes";
$query.=" ORDER BY idepreuve ASC,numero_equipe ASC";

//echo $query;
$dbresult= $db->Execute($query);

//	$calendarImage = "<img title=\"Récupérer le calendrier\" src=\"{$module_dir}/images/calendrier.jpg\" class=\"systemicon\" alt=\"Récupérer le calendrier\" />";
//	$podiumImage = "<img title=\"Récupérer le classement de la poule\" src=\"{$module_dir}/images/podium.jpg\" class=\"systemicon\" width=\"16\" height =\"12\" alt=\"Récupérer le classement\" />";
	//echo $query;
	$rowarray= array();
	$rowarray2= array();
	$rowclass = '';
	$array_chpt = array();

		if ($dbresult && $dbresult->RecordCount() > 0)
  		{
    			$comp_ops = new compositionsbis;
			while ($row= $dbresult->FetchRow())
      			{
				$details_groupe = $gp_ops->details_groupe($row['liste_id']);
				$onerow= new StdClass();				
				$onerow->rowclass= $rowclass;				
				$idepreuve = $row['idepreuve'];
				$onerow->libequipe =  $row['libequipe'];
				$onerow->friendlyname= $row['friendlyname'];
				$onerow->idepreuve = $comp_ops->nom_compet($row['idepreuve']);
				$onerow->nb_joueurs = $row['nb_joueurs'];
				$onerow->capitaine = $adh_ops->get_name($row['capitaine']);
				$onerow->liste_id = $details_groupe['nom'];//liste_id;				
				$onerow->editlink= $this->CreateLink($id, 'add_edit_equipe', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('record_id'=>$row['id']));
				$onerow->delete= $this->CreateLink($id, 'delete', $returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array('obj'=>'delete_eq','record_id'=>$row['id']));
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
			
      			}
			
  		}
		$yesimage = $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('true'),'','','systemicon');
		$noimage = $themeObject->DisplayImage('icons/system/stop.gif', $this->Lang('false'),'','','systemicon');
		$smarty->assign('yes', $yesimage);
		$smarty->assign('no', $noimage);
	
		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);	

echo $this->ProcessTemplate('equipes.tpl');


#
# EOF
#
?>