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
$comp_ops = new compositionsbis;
$liste_epreuves = $comp_ops->liste_epreuves();
//var_dump($liste_epreuves_equipes);
for($i=0; $i<=50;$i++)
{
//	echo $i;
	$liste_journees[$i] = $i;
}
//var_dump($liste_journees); 
$liste_phase = array("1"=>"1","2"=>"2");
$OuiNon = array("Inactif"=>"0", "Actif"=>"1");
$edit = 0; //variable pour savoir s'il s'agit d'un ajout ou d'une modification
if(isset($params['record_id']) && $params['record_id'] !="")
{
		$record_id = $params['record_id'];
		$edit = 1;
		$details = $comp_ops->details_epreuve($record_id);
		$actif = $details['actif'];
		$smarty->assign('record_id',$this->CreateInputHidden($id,'record_id',$record_id));	
}

if(!isset($actif))	
{
	$actif = 1;
}

	//on construit le formulaire
	$smarty->assign('formstart',
			    $this->CreateFormStart( $id, 'do_add_epreuve', $returnid ) );

	
			

	$smarty->assign('edition',
			$this->CreateInputHidden($id,'edition',$edit));
	$smarty->assign('libelle',
			$this->CreateInputText($id,'libelle',(isset($details['libelle'])?$details['libelle']:""), 50, 150));
	$smarty->assign('description',
			$this->CreateInputText($id, 'description',(isset($details['description'])?$details['description']:""), 50, 150));
	$smarty->assign('actif',
			$this->CreateInputDropdown($id,'actif',$OuiNon, $selectedvalue=$actif));						
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));


	$smarty->assign('formend',
			$this->CreateFormEnd());
echo $this->ProcessTemplate('add_edit_epreuve.tpl');

#
# EOF
#
?>