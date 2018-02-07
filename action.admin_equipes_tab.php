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
$ping = new Ping();
$saison_courante = $ping->GetPreference('saison_en_cours');
$phase_courante = $ping->GetPreference('phase_en_cours');
$saison_en_cours = (isset($params['saison_en_cours']))?$params['saison_en_cours']:$saison_courante;
$phase_en_cours = (isset($params['phase_en_cours']))?$params['phase_en_cours']:$phase_courante;
if($phase_courante == 2 && $phase_en_cours == 2)
{
	$smarty->assign('phase', $this->CreateLink($id, 'defaultadmin',$returnid, '<= Phase 1', array("activetab"=>"equipes", "phase_en_cours"=>"1")));
}
elseif($phase_courante == 2 && $phase_en_cours == 1)
{
	$smarty->assign('phase', $this->CreateLink($id, 'defaultadmin',$returnid, 'Phase 2 =>', array("activetab"=>"equipes", "phase_en_cours"=>"2")));
}
$parms = array();
$query = "SELECT id,ref_equipe, libequipe, friendlyname, idepreuve, clt_mini, points_maxi FROM ".cms_db_prefix()."module_compositions_equipes  WHERE saison = ? AND phase = ?";
$query.=" ORDER BY idepreuve ASC,numero_equipe ASC";
$parms['saison'] = $saison_en_cours;
$parms['phase'] = $phase_en_cours;
//echo $query;
$dbresult= $db->Execute($query,$parms);

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
				
				$idepreuve = $row['idepreuve'];
				$onerow->ref_equipe = $row['ref_equipe'];
				$onerow->libequipe=  $row['libequipe'];
				$onerow->friendlyname= $row['friendlyname'];
				$onerow->idepreuve = $ping_ops->nom_compet($row['idepreuve']);
				$onerow->clt_mini = $row['clt_mini'];
				$onerow->points_maxi = $row['points_maxi'];
				
			//	$onerow->view= $this->createLink($id, 'admin_poules_tab3', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view_results'), '', '', 'systemicon'),array('active_tab'=>'equipes','libequipe'=>$row['libequipe'],"record_id"=>$row['eq_id'],"idepreuve"=>$row['idepreuve'])) ;
				$onerow->editlink= $this->CreateLink($id, 'add_edit_equipe', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('record_id'=>$row['ref_equipe']));
				

				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
			
      			}
			
  		}
		$yesimage = $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('true'),'','','systemicon');
		$noimage = $themeObject->DisplayImage('icons/system/stop.gif', $this->Lang('false'),'','','systemicon');
		$smarty->assign('yes', $yesimage);
		$smarty->assign('no', $noimage);
		$smarty->assign('import_from_ping',
				$this->CreateLink($id, 'import_from_ping', $returnid,$contents='Récupérer les équipes depuis le module Ping'));
	
		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		//on prépare le second form d'action de masse
	

echo $this->ProcessTemplate('equipes.tpl');


#
# EOF
#
?>