<?php
if( !isset($gCms) ) exit;
if (!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
//debug_display($params, 'Parameters');
$db =& $this->GetDb();
global $themeObject;
$ping = cms_utils::get_module('Ping');
//$ping_ops = new ping_admin_ops;
$liste_epreuves_equipes = $ping->liste_epreuves_equipes();


//$ping = new Ping;
$saison_courante = $ping->GetPreference('saison_en_cours');
$phase_courante = $ping->GetPreference('phase_en_cours');
$saison_en_cours = (isset($params['saison_en_cours']))?$params['saison_en_cours']:$saison_courante;
$phase_en_cours = (isset($params['phase_en_cours']))?$params['phase_en_cours']:$phase_courante;
$items_phase = array("Phase 1"=>"1", "Phase 2"=>"2");
if(isset($params['idepreuve']))	
{
	$idepreuve = $params['idepreuve'];
	$key_idepreuve = array_values($liste_epreuves_equipes);
	$key2_idepreuve = array_search($idepreuve,$key_idepreuve);
}
else
{
	$key2_idepreuve = 0;
	$idepreuve = '1073';
}
if(isset($params['phase']))	
{
	$phase = $params['phase'];
}
else
{
	$phase = $phase_en_cours;
}

$smarty->assign('formstart',$this->CreateFormStart($id,'defaultadmin','', 'post', '',false,'',array('activetab'=>'brulage')));

$smarty->assign('idepreuve', 
		$this->CreateInputDropdown($id,'idepreuve', $liste_epreuves_equipes,$selectedIndex=$key2_idepreuve,$selectedvalue=$idepreuve));
$smarty->assign('phase', 
		$this->CreateInputDropdown($id,'phase', $items_phase,$selectedIndex=$phase,$selectedvalue=$phase));
$smarty->assign('submitfilter',
		$this->CreateInputSubmit($id,'submitfilter',$this->Lang('filtres')));
$smarty->assign('formend',$this->CreateFormEnd());

$parms = array();
$query = "SELECT id, idepreuve, licence, J1, J2, J3, J4, J5, J6, J7,J8, J9, J10, J11, J12, J13, J14, phase, saison FROM ".cms_db_prefix()."module_compositions_brulage  WHERE saison = ? AND phase = ? AND idepreuve = ? ORDER BY id ASC";
//$query.=" ORDER BY idepreuve ASC,numero_equipe ASC";
$parms['saison'] = $saison_en_cours;
$parms['phase'] = $phase;
$parms['idepreuve'] = $idepreuve;
//echo $query;
$dbresult= $db->Execute($query,$parms);

//	$calendarImage = "<img title=\"Récupérer le calendrier\" src=\"{$module_dir}/images/calendrier.jpg\" class=\"systemicon\" alt=\"Récupérer le calendrier\" />";
//	$podiumImage = "<img title=\"Récupérer le classement de la poule\" src=\"{$module_dir}/images/podium.jpg\" class=\"systemicon\" width=\"16\" height =\"12\" alt=\"Récupérer le classement\" />";
	//echo $query;
	$ping = new adherents_spid;
	$rowarray= array();
	$rowclass = '';

		if ($dbresult && $dbresult->RecordCount() > 0)
  		{
    			while ($row= $dbresult->FetchRow())
      			{
				$onerow= new StdClass();
				$onerow->rowclass= $rowclass;
				$onerow->idepreuve = $row['idepreuve'];
				$onerow->licence=  $ping->get_name($row['licence']);
				$onerow->J1= $comp_ops->get_equipe_official_number($row['J1']);
				$onerow->J2= $comp_ops->get_equipe_official_number($row['J2']);
				$onerow->J3= $comp_ops->get_equipe_official_number($row['J3']);
				$onerow->J4= $comp_ops->get_equipe_official_number($row['J4']);
				$onerow->J5= $comp_ops->get_equipe_official_number($row['J5']);
				$onerow->J6= $comp_ops->get_equipe_official_number($row['J6']);
				$onerow->J7= $comp_ops->get_equipe_official_number($row['J7']);
				$onerow->J8= $comp_ops->get_equipe_official_number($row['J8']);
				$onerow->J9= $comp_ops->get_equipe_official_number($row['J9']);
				$onerow->J10= $comp_ops->get_equipe_official_number($row['J10']);
				$onerow->J11= $comp_ops->get_equipe_official_number($row['J11']);
				$onerow->J12= $comp_ops->get_equipe_official_number($row['J12']);
				$onerow->J13= $comp_ops->get_equipe_official_number($row['J13']);
				$onerow->J14= $comp_ops->get_equipe_official_number($row['J14']);
				$onerow->phase= $row['phase'];
				$onerow->saison= $row['saison'];
				
			
				
			//	$onerow->view= $this->createLink($id, 'admin_poules_tab3', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view_results'), '', '', 'systemicon'),array('active_tab'=>'equipes','libequipe'=>$row['libequipe'],"record_id"=>$row['eq_id'],"idepreuve"=>$row['idepreuve'])) ;
				$onerow->editlink= $this->CreateLink($id, 'add_edit_brulage', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('record_id'=>$row['id']));
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
			
      			}
			
  		}
		
	
		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		//on prépare le second form d'action de masse
	

echo $this->ProcessTemplate('brulage.tpl');


#
# EOF
#
?>