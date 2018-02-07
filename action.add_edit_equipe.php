<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
global $themeObject;
//debug_display($params, 'Parameters');
//$ping = cms_utils::get_module('Ping');

if(isset($params['record_id']) && $params['record_id'] !="")
{
		$record_id = $params['record_id'];
		$edit = 1;
		$query = "SELECT libequipe, friendlyname, nb_joueurs, clt_mini, points_maxi FROM ".cms_db_prefix()."module_compositions_equipes WHERE ref_equipe = ?";
		$dbresult = $db->Execute($query, array($record_id));
		$compt = 0;
		while ($dbresult && $row = $dbresult->FetchRow())
		{
			$compt++;
			$libequipe = $row['libequipe'];
			$friendlyname = $row['friendlyname'];
			$nb_joueurs = $row['nb_joueurs'];
			$clt_mini = $row['clt_mini'];
			$points_maxi = $row['points_maxi'];
		}
}
else
{
	//on prépare un autre formulaire
	//Liste des compétitions par équipes disponibles
	$ping = new ping_admin_ops;
	$liste_epreuves = $ping->liste_epreuves_equipes();
	//Qqs valeurs par défaut

	$smarty->assign('idepreuve',
			$this->CreateInputDropdown($id, 'idepreuve',$liste_epreuves));
	
}
	$OuiNon = array("Non"=>"0", "Oui"=>"1");//on construit le formulaire
	$smarty->assign('formstart',
			    $this->CreateFormStart( $id, 'do_add_edit_equipe', $returnid ) );

	
	$smarty->assign('record_id',$this->CreateInputHidden($id,'record_id',$record_id));		

	
	$smarty->assign('libequipe',
			$this->CreateInputText($id,'libequipe',(isset($libequipe)?$libequipe:""), 30, 150));
	$smarty->assign('friendlyname',
			$this->CreateInputText($id,'friendlyname',(isset($friendlyname)?$friendlyname:""), 30, 150));
	$smarty->assign('nb_joueurs',
			$this->CreateInputText($id,'nb_joueurs',(isset($nb_joueurs)?$nb_joueurs:""), 30, 150));
							
	$smarty->assign('clt_mini',
			$this->CreateInputText($id,'clt_mini',(isset($clt_mini)?$clt_mini:"0"), 30, 150));
	$smarty->assign('points_maxi',
			$this->CreateInputText($id,'points_maxi',(isset($points_maxi)?$points_maxi:"0"), 30, 150));
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));


	$smarty->assign('formend',
			$this->CreateFormEnd());
echo $this->ProcessTemplate('add_edit_equipe.tpl');

#
# EOF
#
?>