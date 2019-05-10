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
//$ping = new Ping();
$adh_ops = new adherents_spid;
$smarty->assign('add_edit_absence',$this->CreateLink($id, 'add_edit_absence',$returnid, 'Ajouter une absence'));
$query = "SELECT id,licence, date_debut, date_fin FROM ".cms_db_prefix()."module_compositions_absences";//"  WHERE saison = ? AND phase = ?";
$query.=" ORDER BY date_debut ASC";

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
    			while ($row= $dbresult->FetchRow())
      			{ 
				$onerow= new StdClass();
				$ping_ops = new ping_admin_ops;
				$onerow->rowclass= $rowclass;
				
				
				$onerow->joueur = $adh_ops->get_name($row['licence']);
				$onerow->date_debut=  $row['date_debut'];
				$onerow->date_fin= $row['date_fin'];
				//$onerow->view= $this->createLink($id, 'admin_poules_tab3', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view_results'), '', '', 'systemicon'),array('active_tab'=>'equipes','libequipe'=>$row['libequipe'],"record_id"=>$row['eq_id'],"idepreuve"=>$row['idepreuve'])) ;
				$onerow->editlink= $this->CreateLink($id, 'add_edit_absence', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('record_id'=>$row['id']));
				$onerow->delete= $this->CreateLink($id, 'delete', $returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array('obj'=>'absence','ref_action'=>$row['id']));
				$onerow->send= $this->CreateLink($id, 'send_absence', $returnid, $themeObject->DisplayImage('icons/system/send.gif', $this->Lang('send'), '', '', 'systemicon'), array('record_id'=>$row['id']));
				

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
		//on prépare le second form d'action de masse
	

echo $this->ProcessTemplate('absences.tpl');


#
# EOF
#
?>